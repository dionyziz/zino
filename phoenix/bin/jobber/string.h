#ifndef CADOR_STRING
#define CADOR_STRING

#include <string> // STL
#include <vector>
#include "error.h"

using namespace std;

bool isalphanumeric( const string& );
string trim( const string& , const string& = " " );
vector< string > explode( const string& , const string& , int = 0 );
string strtolower( const string& );
string strtoupper( const string& );
string stringmap( const string& , int ( * ) ( int ) );

#endif

