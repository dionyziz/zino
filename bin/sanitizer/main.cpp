#include <iostream>
#include <string>
#include "sanitizer.h"

using namespace std;

int main() {
    string source;

    cin.width( 10000 );
    cin >> source;

    Sanitizer s( source );
    s.AllowTag( "div" );
    s.AllowTag( "p" );
    s.AllowAttribute( "div", "class" );
    s.AllowAttribute( "p", "class" );

    s.GetXHTML();
}
