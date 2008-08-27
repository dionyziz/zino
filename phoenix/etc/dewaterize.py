#!/usr/bin/env python

import sys
import os

functions = ( 
    'w_assert',
    '$water->Notice',
    '$water->Trace',
    '$water->Warning'
 )

def decommentized( f ):
    

def dewaterized( f ):
    new = []
    removeGlobal = True
    toAppend = True
    for line in f:
        stripped = line.strip()
        for func in functions:
            if stripped.startswith( func ):
                toAppend = False
        if toAppend:
            new.append( line )
        if stripped.endswith( ';' ):
            toAppend = True
    for line in new:
        if '$water' in line:
            removeGlobal = False
            break
    contents = removeGlobal and [ line for line in new if 'global $water' not in line ] or new
    return ''.join( contents )

def optimized( directory, extensions ):
    for root, subdirs, files in os.walk( directory ):
        try:
            files.remove( 'water.php' )
        except ValueError:
            pass
        for filename in files:
            for ext in extensions:
                if filename.endswith( ext ):
                    name = os.path.join( root, filename ) 
                    f = open( name )
                    filtered = dewaterized( decommentized( f.read() ) )
                    f.close()
                    f = open( name, 'w' )
                    f.write( filtered )
                    f.close()
                    break

if __name__ == '__main__':
    try:
        target = sys.argv[ 1 ]
    except IndexError:
        target = '.'
    exts = sys.argv[ 2: ] or ( 'php', )
    dewaterize( target, exts )
