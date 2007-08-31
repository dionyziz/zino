#ifndef ENTITIES_H
#define ENTITIES_H

#include <iostream>
#include <string>
#include <map>

using namespace std;

string ConvertEntity( char c ) {
    switch ( c ) {
        case '"':
            return "&quot;";
        case '\'':
            return "&apos;";
        case '&':
            return "&amp;";
        case '<':
            return "&lt;";
        case '>':
            return "&gt;";
        default:
            string en = "";
            en.append( 1, c );
            return en;
    }
}

bool IsEntity( string s ) {
    map< string, string > HTMLEntities;

    HTMLEntities[ "&quot;" ] = "\"";
    HTMLEntities[ "&apos;" ] = "'";
    HTMLEntities[ "&amp;" ] = "&";
    HTMLEntities[ "&lt;" ] = "<";
    HTMLEntities[ "&gt;" ] = ">";
    HTMLEntities[ "&nbsp;" ] = " ";
    HTMLEntities[ "&iexcl;" ] = "";
    HTMLEntities[ "&curren;" ] = "";
    HTMLEntities[ "&cent;" ] = "";
    HTMLEntities[ "&pound;" ] = "";
    HTMLEntities[ "&yen;" ] = "";
    HTMLEntities[ "&brvbar;" ] = "";
    HTMLEntities[ "&sect;" ] = "";
    HTMLEntities[ "&uml;" ] = "";
    HTMLEntities[ "&copy;" ] = "";
    HTMLEntities[ "&ordf;" ] = "";
    HTMLEntities[ "&laquo;" ] = "";
    HTMLEntities[ "&not;" ] = "";
    HTMLEntities[ "&shy;" ] = "";
    HTMLEntities[ "&reg;" ] = "";
    HTMLEntities[ "&trade;" ] = "";
    HTMLEntities[ "&macr;" ] = "";
    HTMLEntities[ "&deg;" ] = "";
    HTMLEntities[ "&plusmn;" ] = "";
    HTMLEntities[ "&sup2;" ] = "";
    HTMLEntities[ "&sup3;" ] = "";
    HTMLEntities[ "&acute;" ] = "";
    HTMLEntities[ "&micro;" ] = "";
    HTMLEntities[ "&para;" ] = "";
    HTMLEntities[ "&middot;" ] = "";
    HTMLEntities[ "&cedil;" ] = "";
    HTMLEntities[ "&sup1;" ] = "";
    HTMLEntities[ "&ordm;" ] = "";
    HTMLEntities[ "&raquo;" ] = "";
    HTMLEntities[ "&frac14;" ] = "";
    HTMLEntities[ "&frac12;" ] = "";
    HTMLEntities[ "&frac34;" ] = "";
    HTMLEntities[ "&iquest;" ] = "";
    HTMLEntities[ "&times;" ] = "";
    HTMLEntities[ "&divide;" ] = "";
    HTMLEntities[ "&Agrave;" ] = "";
    HTMLEntities[ "&Aacute;" ] = "";
    HTMLEntities[ "&Acirc;" ] = "";
    HTMLEntities[ "&Atilde;" ] = "";
    HTMLEntities[ "&Auml;" ] = "";
    HTMLEntities[ "&Aring;" ] = "";
    HTMLEntities[ "&Aelig;" ] = "";
    HTMLEntities[ "&Ccedil;" ] = "";
    HTMLEntities[ "&Egrave;" ] = "";
    HTMLEntities[ "&Eacute;" ] = "";
    HTMLEntities[ "&Ecirc;" ] = "";
    HTMLEntities[ "&Euml;" ] = "";
    HTMLEntities[ "&Igrave;" ] = "";
    HTMLEntities[ "&Iacute;" ] = "";
    HTMLEntities[ "&Icirc;" ] = "";
    HTMLEntities[ "&Iuml;" ] = "";
    HTMLEntities[ "&ETH;" ] = "";
    HTMLEntities[ "&Ntilde;" ] = "";
    HTMLEntities[ "&Ograve;" ] = "";
    HTMLEntities[ "&Oacute;" ] = "";
    HTMLEntities[ "&Ocirc;" ] = "";
    HTMLEntities[ "&Otilde;" ] = "";
    HTMLEntities[ "&Ouml;" ] = "";
    HTMLEntities[ "&Oslash;" ] = "";
    HTMLEntities[ "&Ugrave;" ] = "";
    HTMLEntities[ "&Uacute;" ] = "";
    HTMLEntities[ "&Ucirc;" ] = "";
    HTMLEntities[ "&Uuml;" ] = "";
    HTMLEntities[ "&Yacute;" ] = "";
    HTMLEntities[ "&THORN;" ] = "";
    HTMLEntities[ "&szlig;" ] = "";
    HTMLEntities[ "&agrave;" ] = "";
    HTMLEntities[ "&aacute;" ] = "";
    HTMLEntities[ "&acirc;" ] = "";
    HTMLEntities[ "&atilde;" ] = "";
    HTMLEntities[ "&auml;" ] = "";
    HTMLEntities[ "&aring;" ] = "";
    HTMLEntities[ "&aelig;" ] = "";
    HTMLEntities[ "&ccedil;" ] = "";
    HTMLEntities[ "&egrave;" ] = "";
    HTMLEntities[ "&eacute;" ] = "";
    HTMLEntities[ "&ecirc;" ] = "";
    HTMLEntities[ "&euml;" ] = "";
    HTMLEntities[ "&igrave;" ] = "";
    HTMLEntities[ "&iacute;" ] = "";
    HTMLEntities[ "&icirc;" ] = "";
    HTMLEntities[ "&iuml;" ] = "";
    HTMLEntities[ "&eth;" ] = "";
    HTMLEntities[ "&ntilde;" ] = "";
    HTMLEntities[ "&ograve;" ] = "";
    HTMLEntities[ "&oacute;" ] = "";
    HTMLEntities[ "&ocirc;" ] = "";
    HTMLEntities[ "&otilde;" ] = "";
    HTMLEntities[ "&ouml;" ] = "";
    HTMLEntities[ "&oslash;" ] = "";
    HTMLEntities[ "&ugrave;" ] = "";
    HTMLEntities[ "&uacute;" ] = "";
    HTMLEntities[ "&ucirc;" ] = "";
    HTMLEntities[ "&uuml;" ] = "";
    HTMLEntities[ "&yacute;" ] = "";
    HTMLEntities[ "&thorn;" ] = "";
    HTMLEntities[ "&yuml;" ] = "";
    HTMLEntities[ "&OElig;" ] = "";
    HTMLEntities[ "&oelig;" ] = "";
    HTMLEntities[ "&Scaron;" ] = "";
    HTMLEntities[ "&scaron;" ] = "";
    HTMLEntities[ "&Yuml;" ] = "";
    HTMLEntities[ "&circ;" ] = "";
    HTMLEntities[ "&tilde;" ] = "";
    HTMLEntities[ "&ensp;" ] = "";
    HTMLEntities[ "&emsp;" ] = "";
    HTMLEntities[ "&thinsp;" ] = "";
    HTMLEntities[ "&zwnj;" ] = "";
    HTMLEntities[ "&zwj;" ] = "";
    HTMLEntities[ "&lrm;" ] = "";
    HTMLEntities[ "&rlm;" ] = "";
    HTMLEntities[ "&ndash;" ] = "";
    HTMLEntities[ "&mdash;" ] = "";
    HTMLEntities[ "&lsquo;" ] = "";
    HTMLEntities[ "&rsquo;" ] = "";
    HTMLEntities[ "&sdquo;" ] = "";
    HTMLEntities[ "&ldquo;" ] = "";
    HTMLEntities[ "&rdquo;" ] = "";
    HTMLEntities[ "&bdquo;" ] = "";
    HTMLEntities[ "&dagger;" ] = "";
    HTMLEntities[ "&Dagger;" ] = "";
    HTMLEntities[ "&hellip;" ] = "";
    HTMLEntities[ "&permil;" ] = "";
    HTMLEntities[ "&lsaquo;" ] = "";
    HTMLEntities[ "&rsaquo;" ] = "";
    HTMLEntities[ "&euro;" ] = "";

    return HTMLEntities.find( s ) != HTMLEntities.end();
}

#endif
