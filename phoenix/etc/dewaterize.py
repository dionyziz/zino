#!/usr/bin/env python

import sys
import os

functions = (
	'w_assert',
	'$water->Notice',
	'$water->Trace',
	'$water->Warning'
)

def optimize(filename):
	new = []
	toAppend = True
	f = open(filename)
	for line in f:
		stripped = line.strip()
		for f in functions:
			if stripped.startswith(f):
				toAppend = False
		if toAppend:
			new.append(line)
		if stripped.endswith(';'):
			toAppend = True
	f.close()
	f = open(filename, 'w')
	f.write(''.join(new))
	f.close()

def dewaterize(directory, extensions):
	for root, subdirs, files in os.walk(directory):
		for filename in files:
			for ext in extensions:
				if filename.endswith(ext):
					optimize(os.path.join(root, filename))
					break

if __name__ == '__main__':
	exts = sys.argv[1:] or ('php',)
	deassertize('.', exts)
