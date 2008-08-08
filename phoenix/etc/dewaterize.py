#!/usr/bin/env python

import sys
import os

functions = ( 
    'w_assert',
    '$water->Notice',
    '$water->Trace',
    '$water->Warning'
 )

def optimize( filename ):
    new = []
    removeGlobal = True
    toAppend = True
    f = open( filename )
    for line in f:
        stripped = line.strip()
        for func in functions:
            if stripped.startswith( func ):
                toAppend = False
        if toAppend:
            new.append( line )
        if stripped.endswith( ';' ):
            toAppend = True
    f.close()
    for line in new:
        if '$water' in line:
            removeGlobal = False
            break
    contents = removeGlobal and [ line for line in new if 'global $water' not in line ] or new
    f = open( filename, 'w' )
    f.write( ''.join( contents ) )
    f.close()

def dewaterize( directory, extensions ):
    for root, subdirs, files in os.walk( directory ):
        try:
            files.remove( 'water.php' )
        except ValueError:
            pass
        for filename in files:
            for ext in extensions:
                if filename.endswith( ext ):
                    optimize( os.path.join( root, filename ) )
                    break

if __name__ == '__main__':
    exts = sys.argv[ 1: ] or ( 'php', )
    dewaterize( '.', exts )
