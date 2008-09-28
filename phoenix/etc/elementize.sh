#!/bin/bash

for i in `find ../elements -iname "*.php" ! -path "*svn*"`; do
	echo $i":"
	sed 's/function Element\([^(]*\)/class Element\1 extends Element {\n		public function Render/' $i >$i.processed
	vim -s elementize.vim $i.processed
	echo "	}" >>$i.processed
	echo "?>" >>$i.processed
	mv $i.processed $i
done

