/*
    File: errors.cpp
    Description: A PHP-water-emulative error handling and logging script
    Developer: Dionyziz
*/

#include "error.h"

Tracer Trace( 0 );
Tracer Notice( 1 );
Tracer Warning( 2 );

CadorException::CadorException( const string text ) {
    this->mText = text;
    Warning << text;
}

string CadorException::Text() {
    return this->mText;
}

Tracer::Tracer( int level ) {
    this->mTracerLevel = level;
    this->mFollowingUp = false;
}

Tracer::Tracer( int level, bool followup ) {
    this->mTracerLevel = level;
    this->mFollowingUp = followup;
}

Tracer::~Tracer() {
}

