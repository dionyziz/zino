#include <iostream>
#include <string>
#include "sanitizer.h"

using namespace std;

int main() {
    string source = "<div onload=\"start()\" class=\"myprof\"><p>Hello,</p> <p>We<br /> haven't seen you for a long time!</div></p>";

    Sanitizer s( source );
    s.AllowTag( "div" );
    s.AllowTag( "p" );
    s.AllowAttribute( "div", "class" );
    s.AllowAttribute( "p", "class" );

    s.GetXHTML();
}
