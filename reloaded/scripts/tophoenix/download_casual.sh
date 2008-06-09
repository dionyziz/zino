#!/bin/bash

echo "CONTINUE" > ~/migrate/continue

standalonesteps=( 0 1 6 8 9 10 11 12 13 14 15 )
for i in "${standalonesteps[@]}"
do
    wget "http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i" -O ~/migrate/$i.sql.gz
done

offsetsteps=( 2 3 4 5 7 )
for i in "${offsetsteps[@]}"
do
    offset=0
    while true; do
        wget "http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i&testoffset=$offset" -O ~/migrate/$i-$offset-test

        diff ~/migrate/continue ~/migrate/$i-$offset-test > /dev/null

        if [[ $? -eq 0 ]]; then
            break;
        fi

        wget "http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i&offset=$offset" -O ~/migrate/$i-$offset.sql.gz

        offset=$(expr $offset + 1)

        sleep 2
    done
done
