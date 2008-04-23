#include "job.h"

#include <set>
#include <vector>
#include <map>
#include <queue>
#include <cstdio>
#include <cstdlib>

#include "string.h"

set< int > priorities; // the different priorities used currently
vector< int > priority_vector; // this vector has p times the priority p
map< int, int > priority_start; // priority => index of priority_vector where priority starts
map< int, queue< job > > jobs; // priority => jobs with that priority
int job_count = 0;

int priority() {
    int p = priority_vector[ rand() % priority_vector.size() ];

    return p;
}

void add_priority( int p ) {
    priorities.insert( p );
    priority_start[ p ] = priority_vector.size();
    for ( int i = 0; i < p; ++i ) {
        priority_vector.push_back( p );
    }
}

void remove_priority( int p ) {
    priorities.erase( p );
    int start = priority_start[ p ];

    priority_vector.erase( priority_vector.begin() + start, priority_vector.begin() + start + p );

    // the start of each priority in priority_vector, that started after p, has changed
    // as p values has been erased
    for ( set< int >::iterator i = priorities.begin(); i != priorities.end(); ++i ) {
        if ( priority_start[ *i ] > start ) {
            priority_start[ *i ] -= p;
        }
    }
}

job job_pop() {
    int p = priority();
    job j = jobs[ p ].front();
    jobs[ p ].pop();

    if ( jobs[ p ].empty() ) { // popped last of a priority
        remove_priority( p );
    }

    --job_count;

    return j;
}

void job_push( job j ) {
    if ( priorities.find( j.priority ) == priorities.end() ) { // new type of priority
        add_priority( j.priority );
    }

    jobs[ j.priority ].push( j );

    ++job_count;
}

bool job_empty() {
    return job_count == 0;
}

int job_size() {
    return job_count;
}

#include <iostream>

job job_create( int id, int type, int priority ) {
    struct tm * now = NULL;
    time_t date = 0;
    date = time( NULL );

    job j;
    j.id = id;
    j.type = type;
    j.date = itoa( (int)date );
    j.priority = priority;

    return j;
}

