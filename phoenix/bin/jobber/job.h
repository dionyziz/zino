#ifndef JOB_H
#define JOB_H

#include <ctime>

using namespace std;

struct job {
    int id;
    int type;
    time_t time;
    int priority;
};

int priority();
void add_priority( int p );
void remove_priority( int p );
job job_pop();
void job_push( job j );
job job_create( int id, int type, int priority );

#endif
