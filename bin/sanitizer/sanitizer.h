#ifndef SANITIZER_H
#define SANITIZER_H

#include <string>
#include <stack>
#include <set>
#include <map>
#include <queue>
#include "htmltag.h"
// #include "entities.h"

using namespace std;

class Sanitizer {
    public:
        /* 
         * Sanitizer constructor
         * Does nothing important, just sets some variables
         *
         * Parameters: 
         * source: the XHTML source that will be sanitized
         */
        Sanitizer( string source );

        /*
         * Sanitizer destructor
         */
        ~Sanitizer();

        /*
         * Sanitizer::AllowTag()
         * This is used for specifying which xhtml tags
         * will be allowed in the source.
         *
         * Parameters:
         * tag: a string containing the tag that will be allowed.
         *      It has to be a valid xhtml tag.
         *
         * Returns:
         * a boolean value indicating if allowing the tag was successful.
         * That is, it will return false if the tag is not valid.
         */
        bool AllowTag( string tag );

        /*
         * Sanitizer::AllowAttribute()
         * This is used for allowing a specific attribute to be
         * used within a specific tag of the source.
         *
         * Parameters:
         * tag: the tag that will allow the attribute
         * attribute: the allowed attribute
         *
         * Returns:
         * The parameters have to be valid xhtml tag and attribute
         * or allowing the attribute will be unsuccessful and the function
         * will return false. Otherwise, it will return a true boolean value.
         */
        bool AllowAttribute( string tag, string attribute);

        /*
         * Sanitizer::GetXHTML()
         * Get the sanitized XHTML source.
         *
         * Parameters: none
         *
         * Returns:
         * A string with the valid XHTML source using only
         * the allowed tags and attributes that are specified.
         */
        string GetXHTML();
    private:
        const string                mSource; // the source to be sanitized
        stack< HTMLTag * >          mParents; // parent tags open
        set< string >               mAllowedTags; // valid, allowed tags
        multimap< string, string >  mAllowedAttributes; // valid allowed attributes with tags
        string                      mXHTML; // the sanitized source

        /*
         * Sanitizer::CreateTag()
         * Create the source of a tag and its children
         *
         * Parameters:
         * tag: the tag whose source will be created
         *
         * Returns: nothing
         */
        void CreateTag( HTMLTag * tag );

        int NextTagStart( string source, int i, bool intag );
        int NextTagEnd( string source, int i );

        /* Sanitizer::IsXHTMLTag
         * Find out if a tag is a valid XHTML tag
         *
         * Parameters:
         * tag: the tag to be examined
         *
         * Returns:
         * a boolean value indicating if the tag is valid
         */
        bool IsXHTMLTag( string tag );
};

#endif
