/*
    File: network.cpp
    Description: An OOP unix-only-compatible network library
    Developer: Dionyziz
*/

#include "network.h"

Sock::Sock() {
    int fileDescriptor;
    
    // create a socket object
    fileDescriptor = socket( PF_INET, 
                          SOCK_STREAM /* communication with reliable TCP/IP connection */ , 
                          0 /* protocol decided by kernel based on type */ );
    if ( fileDescriptor == -1 ) {
        throw CadorException( "Sock: Could not create socket; socket() returned -1" );
    }
    this->Initialize( fileDescriptor );
}

Sock::Sock( const int fileDescriptor ) {
    this->Initialize( fileDescriptor );
}

void Sock::Initialize( const int fileDescriptor ) {
    int nonBlockApplied;
    const int one = 1;
    int sockopt;

    this->mSock = fileDescriptor;
    
    // set socket to non-blocking i/o
    // forking/threading can be handled by the library user
    nonBlockApplied = ioctl( this->mSock, FIONBIO, ( char * )&one ); 
    if ( nonBlockApplied == -1 ) {
        throw CadorException( "Sock: Failed to make socket non-blocking; ioctl() returned -1" );
    }
    sockopt = 1;
    setsockopt( this->mSock, SOL_SOCKET, SO_LINGER, &sockopt, sizeof( sockopt ) );
    setsockopt( this->mSock, SOL_SOCKET, SO_REUSEADDR, &sockopt, sizeof( sockopt ) );
}

int Sock::SocketId() {
    return this->mSock;
}

Sock::~Sock() {
}

Communicator::Communicator() : Sock() { // for completeness; use Communicator( fileDescriptor, remoteinfo ) instead!
    Warning << "Instanciating Communicator using empty constructor!";
}

Communicator::~Communicator() {
}

Communicator::Communicator( const int fileDescriptor, const struct sockaddr_in remoteinfo ) : Sock( fileDescriptor ) {
    this->mConnected = true;
    this->mCallbackOnReceiveDefined = false;
    this->mCallbackOnCloseDefined = false;
    this->mWaitingFor = false;
    this->mRemoteIP = remoteinfo.sin_addr.s_addr;
    this->SetId( 0 );
    this->mSendBuffer = "";
    this->mRecvBuffer = "";
}

bool Communicator::Connected() {
    return this->mConnected;
}

void Communicator::Send( const string& s ) {
    this->mSendBuffer += s;
    this->SendBuffered();
}

void Communicator::Send( const char* s ) {
    string S = s;

    this->Send( S );
}

void Communicator::SetReceiveCallback( void ( *receiveCallback ) ( Communicator* ) ) {
    this->mCallbackOnReceive = receiveCallback;
    this->mCallbackOnReceiveDefined = true;
}

void Communicator::UnsetReceiveCallback() {
    this->mCallbackOnReceiveDefined = false;
}

void Communicator::SetCloseCallback( void ( *closeCallback ) ( Communicator* ) ){
    this->mCallbackOnClose = closeCallback;
    this->mCallbackOnCloseDefined = true;
}

void Communicator::UnsetCloseCallback() {
    this->mCallbackOnCloseDefined = false;
}

void Communicator::SendBuffered() {
    int sent;
    char* data;

    if ( !this->mConnected ) {
        return;
    }
    if ( this->mSendBuffer.length() ) {
        data = ( char* )malloc( this->mSendBuffer.length() * sizeof( char ) );
        for ( int i = 0; i < this->mSendBuffer.length(); ++i ) {
            data[ i ] = this->mSendBuffer[ i ];
        }
        Trace << this->Id() << ": Sending " << this->mSendBuffer.length() << " bytes";
        sent = send( this->mSock, data, this->mSendBuffer.length(), 0 );
        if ( sent == -1 ) { // disconnected?
            Notice << this->Id() << ": Failed to send buffered data";
            return;
        }
        this->mSendBuffer = this->mSendBuffer.substr( sent );
    }
}

void Communicator::CheckForNewData() {
    int ret;
    char buf[ 512 ];

    ret = recv( this->mSock, buf, sizeof( buf ), 0 );
    switch ( ret ) {
        case 0:
        	// connection was closed
            this->Disconnected();
            return;
        case -1:
            // error
            // Trace( errno );
            break;
        default:
            Trace << this->Id() << ": Incoming data from client";
            this->mRecvBuffer.append( buf, ret );
            this->HandleWaits();
            if ( this->mRecvBuffer.size() && !this->mWaitingFor ) {
                if ( this->mCallbackOnReceiveDefined ) {
                    ( this->mCallbackOnReceive )( this );
                }
                else {
                    Notice << this->Id() << ": Unhandled data received from Communicator";
                }
            }
    }
}

string Communicator::RemoteHost() {
    struct in_addr address;
    string s;

    address.s_addr = this->mRemoteIP;
    s = inet_ntoa( address );
    return s;
}

void Communicator::Close() {
    close( this->mSock );
    this->Disconnected();
}

void Communicator::Disconnected() {
    Trace << this->Id() << ": Connection closed by client";
    this->mConnected = false;
    this->mWaitingFor = false;
    if ( this->mCallbackOnCloseDefined ) {
    	( this->mCallbackOnClose ) ( this );
    }
}

void Communicator::Run() {
    // TODO: Run me periodically
    if ( this->mConnected ) {
    	this->CheckForNewData();
        this->SendBuffered();
    }
}

string Communicator::Id() {
    return this->mId;
}

void Communicator::SetId( const int id ) {
    char s[ 50 ];

    sprintf( s, "%s/%i", this->RemoteHost().c_str(), id );
    this->mId = s;
}

void Communicator::WaitFor( const int numberofbytes, const int timeout, void ( *CallbackOnDone )( Communicator* C, const bool, string ) ) {
    Trace << this->Id() << ": Waiting for " << numberofbytes << " bytes";
    if ( this->mWaitingFor ) {
        throw CadorException( "Communicator: Already waiting for data arrival" );
    }
    this->mWaitingFor = true;
    this->mWaitingForString = false;
    this->mWaitMaximumNumberOfBytes = numberofbytes;
    this->mWaitTimeout = timeout;
    this->mWaitStartedAt = clock();
    this->mWaitCallback = CallbackOnDone;
}

void Communicator::WaitUntil( const string s, const int maximumnumberofbytes, const int timeout, void ( *CallbackOnDone )( Communicator* C, const bool, string ) ) {
    Trace << this->Id() << ": Waiting for sequence termination string of length " << s.length() << " or a maximum of " << maximumnumberofbytes << " bytes";
    if ( this->mWaitingFor ) {
        Warning << this->Id() << ": Already waiting for data arrival; skipping";
        return;
    }
    assert( s.length() <= maximumnumberofbytes );
    this->mWaitingFor = true;
    this->mWaitingForString = true;
    this->mWaitMaximumNumberOfBytes = maximumnumberofbytes;
    this->mWaitTimeout = timeout;
    this->mWaitStartedAt = clock();
    this->mWaitCallback = CallbackOnDone;
    this->mWaitForString = s;
}

bool Communicator::HandleStringWaits() {
    string data;
    int pos;

    if ( this->mRecvBuffer.length() >= this->mWaitForString.length() ) {
        // TODO: Optimize this using KMP
        pos = this->mRecvBuffer.find( this->mWaitForString );

        if ( pos == string::npos ) {
            Trace << this->Id() << ": Termination sequence not found";
            // termination sequence not found, check if we have exceeded the given limit
            if ( this->mRecvBuffer.length() > this->mWaitMaximumNumberOfBytes ) {
                if ( this->mRecvBuffer.length() > this->mWaitMaximumNumberOfBytes + this->mWaitForString.length() ) {
                    Notice << this->Id() << ": Maximum data length hard limit exceeded; skipping";
                    this->mWaitingFor = false;
                    ( this->mWaitCallback )( this, false, "" ); // this might modify mWaitingFor
                    return false;
                }
                // soft limit has been exceeded, but the termination sequence might exceed that limit if the actual data does not
                // check for marginal case
                data = this->mRecvBuffer.substr( this->mWaitMaximumNumberOfBytes );
                if ( this->mWaitForString.substr( 0, data.length() ) != data ) {
                    Notice << this->Id() << ": Maximum data length soft limit exceeded with invalid identifier; skipping";
                    this->mWaitingFor = false;
                    ( this->mWaitCallback )( this, false, "" ); // this might modify mWaitingFor
                    return false;
                }
                Trace << this->Id() << ": Soft limit exceeded within allowed bounds";
            }
            // not found yet, wait for next round
            return true;
        }
        // else
        Trace << this->Id() << ": Termination sequence found";
        // found, check if we're within the allows hard limit
        if ( pos > this->mWaitMaximumNumberOfBytes ) {
            Notice << this->Id() << ": Position of termination identifier exceeds maximum data length hard limit; skipping";
            this->mWaitingFor = false;
            ( this->mWaitCallback )( this, false, "" );
            return false;
        }
        // else
        Trace << this->Id() << ": Limits not exceeded";
        // if we got here, we're good
        data = this->mRecvBuffer.substr( 0, pos );
        this->mRecvBuffer = this->mRecvBuffer.substr( pos + this->mWaitForString.length() );
        this->mWaitingFor = false;
        ( this->mWaitCallback )( this, true, data ); // this might modify mWaitingFor
        return false;
    }
    // else // not yet received enough data to check for sentinel
    return true;
}

bool Communicator::HandleLengthWaits() {
    string data;

    if ( this->mRecvBuffer.length() >= this->mWaitMaximumNumberOfBytes ) {
        data = this->mRecvBuffer.substr( 0, this->mWaitMaximumNumberOfBytes );
        this->mRecvBuffer = this->mRecvBuffer.substr( this->mWaitMaximumNumberOfBytes );
        this->mWaitingFor = false;
        ( this->mWaitCallback )( this, true, data ); // this might modify mWaitingFor
        return false;
    }
    // else // still haven't received the required number of bytes
    return true;
}

void Communicator::HandleWaits() {
    while ( this->mWaitingFor ) {
        if ( this->mWaitingForString ) {
            if ( this->HandleStringWaits() ) {
                break;
            }
        }
        else {
            if ( this->HandleLengthWaits() ) {
                break;
            }
        }
    }
}

Server::Server() : Sock() {
    this->mListening = false;
    this->mPort = 0;
    this->mBacklog = 10;
    this->mCallbackOnAcceptDefined = false;
    this->mNumClients = 0;
}

Server::~Server() {
    this->StopListening();
}

void Server::Listen() {
    int bound;
    
    if ( this->mListening ) {
        // already listening for connections
        return;
    }
    assert( this->mPort > 0 );
    this->mLocalAddress.sin_family = AF_INET; // host byte order
    this->mLocalAddress.sin_port = htons( this->mPort ); // network byte order
    this->mLocalAddress.sin_addr.s_addr = htonl( INADDR_ANY ); // allow connections from any host
    memset( &( this->mLocalAddress.sin_zero ), '\0', 8 ); // nullify the rest of the struct
    
    bound = bind( this->mSock, 
                  ( struct sockaddr * )&this->mLocalAddress, 
                  sizeof( struct sockaddr ) );
    if ( bound == -1 ) {
        throw CadorException( "Server: Could not bind socket to name; bind() returned -1" );
    }

    listen( this->mSock, this->mBacklog ); // listen for connections
    Trace << "Listening for connections new connetions on port " << this->mPort;
    this->mListening = true;
}

void Server::StopListening() {
    if ( !this->mListening ) {
        // not listening
        return;
    }
    Trace << "Stopped listening for connections";
    close( this->mSock );
    this->mListening = false;
}

void Server::SetPort( const int port ) {
    assert( port > 0 );
    assert( port < 65536 );
    
    if ( this->mPort != port ) {
        this->mPort = port;
        this->Reset();
    }
}

void Server::SetBacklog( const int backlog ) {
    assert( backlog >= 1 );
    assert( backlog < 100 );
    
    if ( this->mBacklog != backlog ) {
        this->mBacklog = backlog;
        this->Reset();
    }
}

void Server::SetAcceptCallback( void ( *acceptCallback ) ( Server*, Communicator* ) ) {
    this->mCallbackOnAccept = acceptCallback;
    this->mCallbackOnAcceptDefined = true;
}

void Server::UnsetAcceptCallback() {
    this->mCallbackOnAcceptDefined = false;
}

void Server::Reset() {
    if ( this->mListening ) {
        this->StopListening();
        this->Listen();
    }
}

void Server::CheckForConnectionRequests() {
    socklen_t sin_size;
    struct sockaddr_in remoteinfo;
    int accepted;
    
    sin_size = sizeof( struct sockaddr_in );
    // Either fork()ing or branching to new thread can be handled by the user after the callback
    // releases immediately
    accepted = accept( this->mSock, ( struct sockaddr* )&remoteinfo, &sin_size );
    if ( accepted == -1 ) {
        // no connection currently present
        return;
    }
    this->Accepted( accepted, remoteinfo );
}

void Server::Accepted( const int fileDescriptor, const struct sockaddr_in remoteinfo ) {
    Communicator* thisClient;
    
    thisClient = new Communicator( fileDescriptor, remoteinfo );
    thisClient->SetId( ++this->mNumClients ); 
    Trace << thisClient->Id() << ": New connection request";
    if ( this->mCallbackOnAcceptDefined ) {
        ( this->mCallbackOnAccept )( this, thisClient );
    }
    else {
        Notice << "Unhandled connection request";
    }
}

void Server::Run() {
    // TODO: run this method periodically
    this->CheckForConnectionRequests();
}

/*

Client::Connect() {
    assert( this->mPort > 0 );
    // TODO
}

*/
