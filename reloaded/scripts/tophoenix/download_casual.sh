#!/bin/bash
standalonesteps=( 0 1 3 5 6 8 9 10 11 12 13 14 15 )
for i in "${standalonesteps[@]}"
do
    wget http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i -O >~/migrate/$i.sql.gz
done

