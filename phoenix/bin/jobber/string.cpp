/*
    File: string.cpp
    Description: An extension to the standard ISO C++ string
    Developer: Dionyziz
*/
#include "string.h"

string trim( const string& s, const string& drop ) {
    int spos;
    int epos;
    
    spos = s.find_first_not_of( drop );
    if ( spos == string::npos ) {
        return "";
    }
    epos = s.find_last_not_of( drop );
    
    assert( epos != string::npos ); // would have returned above
    
    ++epos;
    
    return s.substr( spos , epos - spos );
}

vector< string > explode( const string& delimiter, const string& s, int limit ) {
    vector< string > ret;
    int count = 0;
    int prevpos = 0;
    int pos = 0;
    
    if ( limit ) {
        --limit; // 1-based; parameter specifies the maximum length of the returned vector
    }
    pos = s.find( delimiter );
    while ( pos != string::npos ) {
        ++count;
        ret.push_back( s.substr( prevpos , pos - prevpos ) );
        prevpos = pos + 1;
        if ( limit && count >= limit ) {
            ret.push_back( s.substr( prevpos ) );
            return ret;
        }
        pos = s.find( delimiter, prevpos );
    }
    return ret;
}

string strtolower( const string& s ) {
    return stringmap( s , tolower );
}

string strtoupper( const string& s ) {
    return stringmap( s , toupper );
}

string stringmap( const string& s , int ( * mapper ) ( int s ) ) {
    string::const_iterator i;
    string ret;
    int j;
    
    ret = s;
    for ( i = s.begin(), j = 0; i != s.end() ; ++i, ++j ) {
        ret[ j ] = mapper( *i );
    }
    
    return ret;
}

