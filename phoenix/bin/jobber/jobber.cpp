/*
    Job queue daemon

    Developer: abresas
*/


#include <iostream>
#include <vector>

using namespace std;

#include "job.h"
#include "network.h"
#include "client.h"

# define PORT 23302

vector< Client * > clients;

void init() {
    // read jobs from file
}

void connection_received( Server * s, Communicator * com ) {
    Client * c = new Client( com );
    clients.push_back( c );
}

int main() {
    init();

    Server * server = new Server();
    server->SetPort( PORT );
    server->Listen();
    server->SetAcceptCallback( connection_received );

    job_push( job_create( 1, 1, 1 ) );
    job_push( job_create( 2, 1, 1 ) );
    job_push( job_create( 3, 2, 2 ) );
    job_push( job_create( 4, 1, 2 ) );

    while ( true ) {
        sleep( 1 );
        server->Run();
    
        for ( vector< Client * >::iterator i = clients.begin(); i != clients.end(); ++i ) {
            (*i)->Run();
        }
    }
}
