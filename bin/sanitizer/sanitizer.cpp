#include "sanitizer.h"

int main( int argc, char **argv ) {
    const char* input = "Hello, world!";
    TidyBuffer output = {0};
    TidyBuffer errbuf = {0};
    int rc = -1;
    Bool ok;

    TidyDoc tdoc = tidyCreate(); // initialize
    cout << "Tidying:\t\n" << input << endl;

    ok = tidyOptSetBool( tdoc, TidyXhtmlOut, yes ); // XHTML
    if ( ok ) {
        rc = tidySetErrorBuffer( tdoc, &errbuf ); // Capture diagnostics
    }
    if ( rc >= 0 ) {
        rc = tidyParseString( tdoc, input ); // Parse the input
    }
    if ( rc >= 0 ) {
        rc = tidyCleanAndRepair( tdoc ); // Tidy it up!
    }
    if ( rc >= 0 ) {
        rc = tidyRunDiagnostics( tdoc );
    }
    if ( rc > 1 ) { // If error, force output.
        rc = tidyOptSetBool(tdoc, TidyForceOutput, yes) ? rc: -1;
    }
    if ( rc >= 0 ) {
        rc = tidySaveBuffer( tdoc, &output );
    }
    if ( rc >= 0 ) {
        if ( rc > 0 ) {
            cerr << "\nDiagnostics:\n\n" << errbuf.bp;
            cout << output.bp;
        }
        else {
            cerr << "A severe error occurred:\n" << rc;
        }
    }
    
    tidyBufFree( &output );
    tidyBufFree( &errbuf );
    tidyRelease( tdoc );
    
    return rc;
}
