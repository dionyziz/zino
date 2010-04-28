# coding=UTF-8
import sys;

base = "http://alpha.zino.gr/abresas/"

grArticles = { 
    u"ο": "nom", 
    u"του": "gen",
    u"τον": "acc",
    u"οι": "nom",
    u"των": "gen",
    u"τους": "acc", 
    u"η": "nom", 
    u"της": "gen", 
    u"την": "acc",
    u"τις": "gen", 
    u"το": [ "nom", "acc" ]
}

grNouns = {
    u"χρήστης": ( u"χρήστης", "nom" ),
    u"χρήστη": ( u"χρήστης", [ "gen", "acc" ] ),
    u"χρήστες": ( u"χρήστης", [ "nom", "acc" ] ),
    u"χρηστών": ( u"χρήστης", "gen" ),
    u"φωτογραφία": ( u"φωτογραφία", [ "nom", "acc" ] ),
    u"φωτογραφίας": ( u"φωτογραφία", "gen" ),
    u"φωτογραφίες": ( u"φωτογραφία", [ "nom", "acc" ] ),
    u"φωτογραφιών": ( u"φωτογραφία", "gen" ),
    u"ημερολόγιο": ( u"ημερολόγιο", [ "nom", "acc" ] ),
    u"ημερολογίου": ( u"ημερολόγιο", "gen" ),
    u"ημερολόγια": ( u"ημερολόγιο", [ "nom", "acc" ] ),
    u"δημοσκοπήση": ( u"δημοσκόπηση", [ "nom", "acc" ] ),
    u"δημοσκοπήσης": ( u"δημοσκόπηση", "gen" ),
    u"δημοσκοπήσεις": ( u"δημοσκόπηση", [ "nom", "acc" ] ),
    u"δημοσκοπήσεων": ( u"δημοσκόπηση", "gen" ),
    u"φίλος": ( u"φίλος", "nom" ),
    u"φίλου": ( u"φίλος", "gen" ),
    u"φίλο": ( u"φίλος", [ "nom", "acc" ] ),
    u"φίλοι": ( u"φίλος", "nom" ),
    u"φίλων": ( u"φίλος", "gen" ),
    u"φίλους": ( u"φίλος", "acc" ),
    u"αγαπημένο": ( u"αγαπημένο", [ "nom", "acc" ] ),
    u"αγαπημένου": ( u"αγαπημένο", "gen" ),
    u"αγαπημένα": ( u"αγαπημένο", [ "nom", "acc" ] ),
    u"αγαπημένων": ( u"αγαπημένο", "gen" ),
    u"ποιος": ( u"ποιος", "nom" ),
    u"ποιου": ( u"ποιος", "gen" ),
    u"ποιον": ( u"ποιος", "acc" ),
    u"ποιοι": ( u"ποιος", "nom" ),
    u"ποιων": ( u"ποιος", "gen" ),
    u"ποιους": ( u"ποιος", "acc" ),
    u"ποια": ( u"ποιος", [ "nom", "acc" ] ),
    u"ποιας": ( u"ποιος", "gen" ),
    u"ποιες": ( u"ποιος", [ "nom", "acc" ] ),
    u"ποιο": ( u"ποιος", [ "nom", "acc" ] )
}

toPrural = {
    "user": "users",
    "photo": "photos",
    "journal": "journals",
    "poll": "polls"
}

predicatePrefixes = { 
    u"είναι": u"είναι", 
    u"έχει": u"έχει",
    u"έχουν": u"έχει", 
    u"έχουμε": u"έχει", 
    u"έχετε": u"έχει"
}

translate = {
    u"χρήστης": "user",
    u"φωτογραφία": "photo",
    u"ημερολόγιο": "journal",
    u"δημοσκόπηση": "poll",
    u"έχει φίλος": "has friend",
    u"είναι φίλος": "friend of",
    u"έχει αγαπημένο": "has favourite",
    u"είναι αγαπημένο": "favourite of",
    u"ποιος": "X",
}

resources = [ "users", "photos", "journals", "polls" ]
resourceClasses = { "items": [ "photos", "journals", "polls" ] }

predicates = {
    "has friend": { "property": "hasFriends", "type": "bag", "domain": "users", "range": "users", "inverse": "friend of" },
    "friend of": { "property": "friendOf", "type": "bag", "domain": "users", "range": "users", "inverse": "has friend" },
    "has favourite": { "property": "hasFavourite", "type": "bag", "domain": "users", "range": "items", "inverse": "favourite of" },
    "favourite of": { "property": "favouriteOf", "type": "bag", "domain": "items", "range": "users", "inverse": "has favourite" }
}

def getWords():
    l = raw_input( "> " ).rstrip( "\n?" ).split( ' ' )
    u = []
    for w in l:
        u.append( unicode( w, 'UTF-8' ) )
    return u

def analyzeWords( words ):
    prefix = ""
    i = 0
    prevCase = ""
    while i < len( words ):
        w = words[ i ].lower()
        if w in predicatePrefixes:
            prefix = predicatePrefixes[ w ]
            del words[ i ]
            continue
        if w in grArticles:
            prevCase = grArticles[ w ]
            del words[ i ]
            continue
        elif w in grNouns:
            w = grNouns[ w ]
            if len( prefix ): 
                w = ( prefix + " " + w[ 0 ], w[ 1 ] )
                prefix = ""
        else:
            w = ( w, prevCase )
        words[ i ] = w
        i += 1
    return words

def translateWords( words ):
    i = 0
    while i < len( words ):
        w = words[ i ]
        if type( w ).__name__ == "str":
            i += 1
            continue
        w = w[ 0 ].lower()
        if w == u"ο":
            del words[ i ]
            continue
        if w in translate:
            words[ i ] = ( translate[ w ], words[ i ][ 1 ] )
        i += 1
    return words

def getResource( res ):
    if res in toPrural:
        res = toPrural[ res ]
    if res in resources:
        return res
    else:
        return ""

def getPredicate( w ):
    if w[ 0 ] in predicates: return predicates[ w[ 0 ] ]
    else: return {}

def getParts( words, start = 0 ):
    subject = {}
    object = {}
    i = start
    subject[ "resource" ] = getResource( words[ i ] )
    if len( subject[ "resource" ] ):
        i += 1
    subject[ "id" ] = words[ i ][ 0 ]
    subject[ "case" ] = words[ i ][ 1 ]

    i += 1
    predicate = getPredicate( words[ i ] )
    i += 1
    object[ "resource" ] = getResource( words[ i ] )
    if ( len( object[ "resource" ] ) ):
        i += 1
    object[ "id" ] = words[ i ][ 0 ]
    object[ "case" ] = words[ i ][ 1 ]

    subjectRight = type( subject[ "case" ] ).__name__ == "str" and subject[ "case" ] in ( "nom", "" ) or "nom" in subject[ "case" ]
    objectRight = type( subject[ "case" ] ).__name__ != "str" or subject[ "case" ] != "nom"
    if not subjectRight or not objectRight:
        temp = subject
        subject = object
        object = temp

    return ( subject, predicate, object )

def setResource( noun, res ):
    if not noun[ "resource" ]:
        noun[ "resource" ] = res
    return noun

def findVariables( subject, predicate, object ):
    if subject[ "id" ] in ( "X", "Y" ):
        subject[ "type" ] = "var"
    elif subject[ "id" ].isdigit():
        subject[ "type" ] = "id"
    else:
        subject[ "type" ] = "name"
    if object[ "id" ] in ( "X", "Y" ):
        object[ "type" ] = "var"
    elif object[ "id" ].isdigit():
        object[ "type" ] = "id"
    else:
        object[ "type" ] = "name"
    if subject[ "type" ] == "var" and object[ "type" ] != "var" and predicate[ "inverse" ]:
        temp = subject
        subject = object
        object = temp
        predicate = predicates[ predicate[ "inverse" ] ]
    return ( subject, predicate, object )

while True:
    words = getWords()
    print "0:", words
    words = analyzeWords( words )
    print "0,5:", words
    words = translateWords( words )
    print "1:", words
    ( subject, predicate, object ) = getParts( words )
    print "2: subject = ", subject, ", predicate = ", predicate, ", object = ", object

    subject = setResource( subject, predicate[ "domain" ] )
    object = setResource( object, predicate[ "range" ] )
    print "3: subject =", subject, ", predicate = ", predicate, ", object = ", object

    ( subject, predicate, object ) = findVariables( subject, predicate, object )
    print "4: subject =", subject, ", predicate = ", predicate, ", object = ", object

    print "5:", subject[ "resource" ] + ".view( \"?" + subject[ "type" ] + "=" + subject[ "id" ] + "\" )"
