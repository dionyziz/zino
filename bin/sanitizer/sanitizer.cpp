#include "sanitizer.h"

int main( int argc, char **argv ) {
    TidyBuffer output = { 0 };
    TidyBuffer errbuf = { 0 };
    int rc = -1;
    Bool ok;
    string s;

    copy( istreambuf_iterator< char >( cin ), istreambuf_iterator< char >(), back_inserter( s ) );
    
    const char* input = s.c_str();
    
    TidyDoc tdoc = tidyCreate(); // initialize
    cout << "Tidying:\t\n" << input << endl;

    if ( tidyOptSetBool( tdoc, TidyXhtmlOut, yes ) ) { // XHTML
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
