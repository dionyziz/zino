#include <iostream>
#include <string>
#include "sanitizer.h"

using namespace std;

int main() {
    Sanitizer s = Sanitizer();
    
    string tag;
    string line;
    string source;

    /* tags */
    do {
        getline( cin, tag );
        s.AllowTag( tag );
    } while ( tag != "" );

    /* attributes */
    do {
        getline( cin, line );

        if ( line.find( " " ) != string::npos ) {
            string tag = line.substr( 0, line.find( " " ) );
            string att = line.substr( line.find( " " ), line.length() - line.find( " " ) + 1 );

            s.AllowAttribute( tag, att );
        }
    } while ( line != "" );

    do {
        getline( cin, source );
        string nsource = s.GetSource();
        nsource.append( source );
        s.SetSource( nsource );
    } while ( source != "" );
    
    s.Sanitize();
    s.GetXHTML();
}
