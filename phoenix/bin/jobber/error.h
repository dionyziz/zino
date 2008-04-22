#ifndef CADOR_ERROR
#define CADOR_ERROR

#include <iostream>
#include "string.h"
#include "assert.h"

using namespace std;

// extern int errno;

const char* const CADOR_OUTPUT_GREEN  = "\033[32;1m";
const char* const CADOR_OUTPUT_YELLOW = "\033[33;1m";
const char* const CADOR_OUTPUT_RED    = "\033[31;1m";
const char* const CADOR_OUTPUT_RESET  = "\033[0m";
const int E_ERROR_TRACE   = 0;
const int E_ERROR_NOTICE  = 1;
const int E_ERROR_WARNING = 2;

class CadorException {
    public:
        CadorException( const string );
        string Text();
    private:
        string mText;
};

class Tracer {
    private:
        int mTracerLevel;
        bool mFollowingUp;
    public:
        Tracer();
        Tracer( int );
        Tracer( int, bool );
        ~Tracer();
        template < typename T >
        Tracer operator << ( T text ) {
            if ( !this->mFollowingUp ) {
                cout << endl;
                switch ( this->mTracerLevel ) {
                    case 0:
                        cout << CADOR_OUTPUT_GREEN << "Trace: " << CADOR_OUTPUT_RESET;
                        break;
                    case 1:
                        cout << CADOR_OUTPUT_YELLOW << "Notice: " << CADOR_OUTPUT_RESET;
                        break;
                    case 2:
                        cout << CADOR_OUTPUT_RED << "Warning: " << CADOR_OUTPUT_RESET;
                        break;
                }
            }
            cout << text << flush;
            return Tracer( this->mTracerLevel, true );
        }
};

extern Tracer Trace;
extern Tracer Notice;
extern Tracer Warning;

#endif

