#ifndef CLIENT_H
#define CLIENT_H

#include <map>
#include "network.h"

#define CLIENT_PACKET_TIMEOUT 1000

class Client;

using namespace std;

extern map< unsigned int, Client * > Client_FromCommunicator;

class Client {
    public:
        Client( Communicator * c );

        void Initiate();
        void Run();
        string Id();

        static inline void InitiationDone   ( Communicator * c, const bool success, string data )
        { Client_FromCommunicator[ c->SocketId() ]->InitiationCompleted ( success, data );      }
        static inline void StartPop         ( Communicator * c, const bool success, string data )
        { Client_FromCommunicator[ c->SocketId() ]->Pop( success, data );                       }

    protected:
        void InitiationCompleted( const bool success, string data );
        void Pop( const bool success, string data );

    private:
        Communicator * mCommunicator;
};

#endif
