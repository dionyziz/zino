#include <iostream>
#include <string>
#include "sanitizer.h"

using namespace std;

int main() {
    string source;
    string line;

    /* tags */
    do {
        getline( cin, line );
    } while ( line != "" );

    /* attributes */
    do {
        getline( cin, line );
    } while ( line != "" );

    do {
        getline( cin, source );
    } while ( line != "" );
    
    Sanitizer s( source );
    s.AllowTag( "div" );
    s.AllowTag( "p" );
    s.AllowAttribute( "div", "class" );
    s.AllowAttribute( "p", "class" );

    s.GetXHTML();
}
