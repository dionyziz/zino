#include <iostream>
#include <string>
#include "sanitizer.h"

using namespace std;

int main() {
    Sanitizer s = Sanitizer();
    
    char source[ 65535 ];
    string tag;
    string line;
    
    ios_base::fmtflags ff;

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

    while ( !cin.eof() ) {
        cin.getline( source, 256 );
        s.SetSource( s.GetSource() + source );
    }


    s.Sanitize();
    s.GetXHTML();
}
