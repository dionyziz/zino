#ifndef JOB_H
#define JOB_H

#include <ctime>
#include <string>

using namespace std;

struct job {
    int id;
    int type;
    string date;
    int priority;
};

int priority();
void add_priority( int p );
void remove_priority( int p );
job job_pop();
void job_push( job j );
job job_create( int id, int type, int priority );
bool job_empty();
int job_size();

#endif
