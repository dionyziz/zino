#include "htmltag.h"
#include "entities.h"

bool isalpha( char c ) {
    return ( ( 'a' <= c && c <= 'z' ) || ( 'A' <= c && c <= 'Z' ) );
}

HTMLTag::HTMLTag( string source, bool forcetext ) : mSource( source ) {
    int length = mSource.length();
    mIsSelfClosingTag = false;
    if ( !forcetext && mSource[ 0 ] == '<' && mSource[ length - 1 ] == '>' ) { // normal tag
        bool namefound          = false;
        bool inattributename    = false;
        bool inattributevalue   = false;
        
        mName = "";
        string attributeName    = "";
        string attributeValue   = "";

        for ( int i = 1; i < length - 1; ++i ) {
            char c = mSource[ i ];
           
            if ( i == 1 && c == '/' ) {
                mIsClosingTag = true;
                continue;
            }
            else if ( i == 1 ) {
                mIsClosingTag = false;
            }
            if ( !namefound && isalpha( c ) ) {
                mName.append( 1, c );
                continue;
            }

            else if ( !namefound ) {
                namefound = true;
            }
            
            if ( isalpha( c ) && !inattributename && !inattributevalue ) {
                inattributename = true;
                attributeName = c;
                continue;
            }
            if ( isalpha( c ) && inattributename ) {
                attributeName.append( 1, c );
            }
            if ( !isalpha( c ) && inattributename ) {
                if ( ( c == '=' && mSource[ i + 1 ] != '"' ) || c == '"' ) {
                    inattributename = false;
                    inattributevalue = true;
                    continue;
                }
                if ( c == '=' ) {
                    continue;
                }
                inattributename = false;
                continue;
            }
            
            if ( inattributevalue && c == '"' ) {
                inattributevalue = false;
                mAttributes[ attributeName ] = attributeValue;
                continue;
            }
            if ( inattributevalue && mSource[ i - 1 ] == '"' || mSource[ i - 1 ] == '=' ) {
                attributeValue = c;
                continue;
            }
            if ( inattributevalue ) {
                attributeValue.append( 1, c );
            }

            if ( i == length - 2 && c == '/' ) {
                mIsSelfClosingTag = true;
            }
        }
    }
    else { // text
        mName = "#text";
    }
}

HTMLTag::~HTMLTag() {
    for ( int i = 0; i < mChildren.size(); ++i ) {
        delete mChildren[ i ];
    }
}

string HTMLTag::Name() {
    return mName;
}

string HTMLTag::Source() {
    return mSource;
}

string HTMLTag::Text() {
    assert( this->IsText() );

    mText = "";
    bool prevWasWhite = false;
    for ( int i = 0; i < mSource.length(); ++i ) {
        char c = mSource[ i ];

        if ( ( c == ' ' || c == '\n' ) && i != mSource.length() - 1 ) {
            prevWasWhite = true;
            continue;
        }
        else if ( c == ' ' || c == '\n' ) {
            mText.append( " " );
            continue;
        }
        else if ( prevWasWhite ) {
            mText.append( " " );
            prevWasWhite = false;
        }

        if ( c == '&' && mSource.find( ';', i ) != string::npos ) {
            int end = mSource.find( ';', i );
            string entity = mSource.substr( i, end - i + 1 );
            if ( IsEntity( entity ) ) {
                mText.append( entity );
                i = end;
                continue;
            }
        }

        mText.append( ConvertEntity( c ) );
    }

    return mText;
}

bool HTMLTag::IsClosingTag() {
    return mIsClosingTag;
}

bool HTMLTag::IsSelfClosingTag() {
    return mIsSelfClosingTag;
}

void HTMLTag::AddChild( HTMLTag * child ) {
    mChildren.push_back( child );
}

void HTMLTag::ChangeToText() {
    mName = "#text";
}

bool HTMLTag::IsText() {
    return mName == "#text";
}

map< string, string > HTMLTag::Attributes() {
    return mAttributes;
}

vector< HTMLTag * > HTMLTag::Children() {
    return mChildren;
}
