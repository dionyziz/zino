#ifndef CADOR_NETWORK
#define CADOR_NETWORK

#include <ctime>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/ioctl.h>
#include "string.h"
#include "error.h"

using namespace std;

const int MAX_HOSTNAME = 200;
const int MAX_CONNECTIONS = 5;
const int MAX_RECV = 500;

class Sock {
    public:
        Sock();
        Sock( int );
        ~Sock();
        virtual int SocketId();
    protected:
        int mSock; // file descriptor for the socket
    private:
        void Initialize( int );
};

class Communicator : public Sock {
    public:
        Communicator();
        ~Communicator();
        Communicator( int, struct sockaddr_in );
        void Close();
        void Send( const string& );
        void Send( const char* );
        void SetReceiveCallback( void ( * ) ( Communicator* ) );
        void UnsetReceiveCallback();
        void SetCloseCallback( void( * ) ( Communicator* ) );
        void UnsetCloseCallback();
        virtual void Run();
        // string GetData( int numberofbytes );
        void WaitFor( int, int, void ( * ) ( Communicator*, const bool, const string ) );
        void WaitUntil( string, int, int, void ( * ) ( Communicator*, const bool, const string ) );
        string RemoteHost();
        void SetId( int );
        string Id();
        bool Connected();
    protected:
        void HandleWaits();
        bool HandleStringWaits();
        bool HandleLengthWaits();
        void CheckForNewData();
        void SendBuffered();
        bool mConnected;
    private:
        void Disconnected();
        string mSendBuffer;
        string mRecvBuffer;
        string mId;
        unsigned long mRemoteIP;
        void ( *mCallbackOnReceive ) ( Communicator* );
        bool mCallbackOnReceiveDefined;
        void ( *mCallbackOnClose ) ( Communicator* );
        bool mCallbackOnCloseDefined;
        
        void ( *mWaitCallback ) ( Communicator*, const bool, const string );
        bool mWaitingFor;
        bool mWaitingForString;
        int mWaitMaximumNumberOfBytes;
        int mWaitTimeout;
        int mWaitStartedAt;
        string mWaitForString;
};

class Server : private Sock {
    public:
        Server();
        ~Server();
        void Listen();
        void StopListening();
        void SetPort( const int );
        void SetBacklog( const int );
        void SetAcceptCallback( void ( * ) ( Server*, Communicator* ) );
        void UnsetAcceptCallback();
        void Run();
    private:
        void CheckForConnectionRequests();
        void Accepted( const int, const struct sockaddr_in );
        void Reset();
        short mPort; // local listening port
        int mBacklog; // backlog number of queued connections
        struct sockaddr_in mLocalAddress;
        bool mListening;
        int mNumClients;
        void ( *mCallbackOnAccept ) ( Server*, Communicator* );
        bool mCallbackOnAcceptDefined;
};

/*
class Client : private Communicator {
    public:
        Connect()
    private:
        int mPort; // remote port
        int mHost; // remote host
}
*/

#endif
