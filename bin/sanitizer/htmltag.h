#ifndef HTMLTAG_H
#define HTMLTAG_H

#include <iostream>
#include <string>
#include <map>
#include <vector>
#include <cassert>

using namespace std;

class HTMLTag {
    public:
        HTMLTag( string source, bool forcetext = false );
        ~HTMLTag();

        string                  Name();
        string                  Source();
        string                  Text();
        bool                    IsClosingTag();
        bool                    IsSelfClosingTag();
        bool                    IsText();
        map< string, string >   Attributes();
        vector< HTMLTag * >     Children();

        void                    AddChild( HTMLTag * );
        void                    ChangeToText();
    private:
        string                  mName;
        string                  mSource;
        string                  mText;
        map< string, string >   mAttributes;
        bool                    mIsClosingTag;
        bool                    mIsSelfClosingTag;
        vector< HTMLTag * >     mChildren;
};

#endif
