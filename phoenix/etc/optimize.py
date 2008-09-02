#!/usr/bin/env python

import sys
import os

functions = ( 'w_assert',
    '$water->Foo',
    '$water->Bar',
    '$water->Hey' )

def decommentized( source ):
    new = []
    inString = False
    inComment = False
    commentStart = False
    for line in source.splitlines():
        backslashes = 0
        for c in line:
            if inString:
                if c == symbol and backslashes % 2:
                    inString = False
            if inComment:
                

def dewaterized( source ):
    new = []
    toAppend = True
    for line in source.splitlines():
        line = line.strip()
        for f in functions:
            if line.startswith( f ):
                toAppend = False
        if toAppend:
            new.append( line )
        if line.endswith( ';' ):
            toAppend = True
    return ''.join( new )

def optimize( directory, extensions ):
    for root, subdirs, files in os.walk( directory ):
        try:
            files.remove( 'water.php' )
        except ValueError:
            pass
        for f in files:
            for e in extensions:
                if f.endswith( e ):
                    sourceFile = open( f )
                    new = dewaterized( decommentized( sourceFile.read() ) )
                    sourceFile.close()
                    sourceFile = open( f, 'w' )
                    sourceFile.write( new )
                    sourceFile.close()
                    break

if __name__ == '__main__':
    try:
        directory = sys.argv[ 1 ]
    except IndexError:
        directory = '.'
    extensions = sys.argv[ 2: ] or ( 'php', )
    optimize( directory, extensions )
