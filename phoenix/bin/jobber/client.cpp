#include "client.h"
#include "error.h"
#include "job.h"

map< unsigned int, Client * > Client_FromCommunicator;

Client::Client( Communicator * c ) {
    this->mCommunicator = c;
    Client_FromCommunicator[ c->SocketId() ] = this;
    this->Initiate();
}

void Client::Run() {
    this->mCommunicator->Run();
}

string Client::Id() {
    return this->mCommunicator->Id();
}

void Client::Initiate() {
    Trace << this->Id() << ": Initiating";
    this->mCommunicator->WaitFor( 4, CLIENT_PACKET_TIMEOUT, &Client::InitiationDone );
}

void Client::InitiationCompleted( const bool success, string data ) {
    if ( !success ) {
        Warning << this->Id() << ": Initiation failed";
        return;
    }
    Trace << "Initiation: " << data;
    if ( data == "POP\n" ) {
        Trace << this->Id() << ": Starting pop";
        this->mCommunicator->WaitFor( 2, CLIENT_PACKET_TIMEOUT, &Client::StartPop );
    }
    else if ( data == "PUS\n" ) {
        Trace << this->Id() << ": Starting push";
    }
}

void Client::Pop( const bool success, string data ) {
    data = data.substr( 0, 1 );

    int n = atoi( data.c_str() );
    Trace << "Pop: " << n;

    string s = "POPPED\n";

    for ( int i = 0; i < n; ++i ) {
        if ( job_empty() ) {
            s += "E\n";
            break;
        }
        job j = job_pop();
        s.append( 1, j.id + 48 );
        s.append( 1, ' ' );
        s.append( 1, j.type + 48 );
        s.append( 1, ' ' );
        s += j.date;
        s.append( 1, ' ' );
        s.append( 1, j.priority + 48 );
        s.append( 1, '\n' );
    }

    this->mCommunicator->Send( s ); 
    this->Initiate();
}
