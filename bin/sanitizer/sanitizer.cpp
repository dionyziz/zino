#include "sanitizer.h"

Sanitizer::Sanitizer() {
}

Sanitizer::~Sanitizer() {
    while ( mParents.size() > 0 ) {
        mParents.pop();
    }
}

void Sanitizer::SetSource( string source ) {
    mSource = source;
}

void Sanitizer::Sanitize() {
    mParents.push( new HTMLTag( "" ) );

    string text = "";
    int i = 0;
    while ( i < mSource.length() ) {
        char c = mSource[ i ];
        int nextstart; 
        if ( c == '<' ) {
            nextstart = NextTagStart( mSource, i + 1, true );
            int endpos = NextTagEnd( mSource, i + 1 );
            if ( endpos != string::npos && ( nextstart == string::npos || endpos < nextstart ) ) {
                if ( text.length() > 0 ) {
                    HTMLTag * textTag = new HTMLTag( text, true );
                    text = "";
                    mParents.top()->AddChild( textTag );
                }
                
                HTMLTag * newTag = new HTMLTag( mSource.substr( i, endpos - i + 1 ), false );
                if ( newTag->IsClosingTag() ) {
                    stack< HTMLTag * > parentscp = mParents;

                    while ( mParents.size() > 0 && mParents.top()->Name() != newTag->Name() ) {
                        mParents.pop();
                    }

                    if ( mParents.size() > 0 ) {
                        mParents.pop();
                    }
                    else {
                        mParents = parentscp;
                        newTag->ChangeToText();
                        mParents.top()->AddChild( newTag );
                    }
                }
                else {
                    mParents.top()->AddChild( newTag );
                    if ( !newTag->IsSelfClosingTag() ) {
                        mParents.push( newTag );
                    }
                }

                i = endpos + 1;

                continue;
            }
        }
        else {
            nextstart = NextTagStart( mSource, i + 1, false );
        }
        text += mSource.substr( i, nextstart - i );
        i = nextstart;
    }

    if ( text.length() > 0 ) {
        HTMLTag * textTag = new HTMLTag( text, true );
        mParents.top()->AddChild( textTag );
    }
}

bool Sanitizer::AllowTag( string tag ) {
    if ( this->IsXHTMLTag( tag ) ) {
        mAllowedTags.insert( tag );

        return true;
    }

    return false;
}

bool Sanitizer::AllowAttribute( string tag, string attribute ) {
    if ( true ) {
        mAllowedAttributes.insert( make_pair( tag, attribute ) );

        return true;
    }

    return false;
}

string Sanitizer::GetXHTML() {
    while ( mParents.size() > 1 ) {
        mParents.pop();
    }

    CreateTag( mParents.top() );
    return "bar";
}

void Sanitizer::CreateTag( HTMLTag * tag ) {
    if ( tag->IsText() ) {
        cout << tag->Text();

        // the root tag ( a "" text tag) has children
        // and they have to be displayed
        vector< HTMLTag * > children = tag->Children();
        for ( int i = 0; i < children.size(); ++i ) {
            CreateTag( children[ i ] );
        }
    }
    else if ( mAllowedTags.find( tag->Name() ) != mAllowedTags.end() ) {
        cout << "<" << tag->Name();
        map< string, string > attributes = tag->Attributes();
        for ( map< string, string >::iterator It = attributes.begin(); It != attributes.end(); ++It ) {
            string name = It->first;
            string value = It->second;
            if ( mAllowedAttributes.find( name ) != mAllowedAttributes.end() ) {
                cout << " " << name << "=\"" << value << "\"";
            }
        }
        if ( tag->IsSelfClosingTag() ) {
            cout << " /";
        }
        cout << ">";

        vector< HTMLTag * > children = tag->Children();
        for ( int i = 0; i < children.size(); ++i ) {
            CreateTag( children[ i ] );
        }

        if ( !tag->IsSelfClosingTag() ) {
            cout << "</" << tag->Name() <<">";
        }
    }
}

int Sanitizer::NextTagStart( string source, int i, bool intag ) {
    if ( !intag ) {
        return source.find( '<', i );
    }

    bool inattr = false;
    while ( i < source.length() ) {
        char c = source[ i ];
        if ( !inattr && c == '<' ) {
            return i;
        }
        if ( c == '"' ) {
            inattr = !inattr;
        }
        ++i;
    }

    return string::npos;
}

int Sanitizer::NextTagEnd( string source, int i ) {
    bool inattr = false;
    while ( i < source.length() ) {
        char c = source[ i ];
        if ( !inattr && c == '>' ) {
            return i;
        }
        if ( c == '"' ) {
            inattr = !inattr;
        }
        ++i;
    }

    return string::npos;
}

bool Sanitizer::IsXHTMLTag( string tag ) {
    return true;
}
