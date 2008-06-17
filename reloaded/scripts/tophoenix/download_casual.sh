#!/bin/bash

echo "CONTINUE" > ~/migrate/continue

standalonesteps=( 0 1 6 8 9 10 11 12 13 14 17 )
for i in "${standalonesteps[@]}"
do
    URL="http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i"
    echo -n "Downloading step " $i "... "
    wget $URL -O ~/migrate/$i.sql.gz 2>/dev/null
    echo "Done"
done

offsetsteps=( 2 3 4 5 7 15 16 )
for i in "${offsetsteps[@]}"
do
    offset=0
    while true; do
        URL="http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i&testoffset=$offset" 
        wget $URL -O ~/migrate/$i-$offset-test 2>/dev/null
        if [[ $? -neq 0 ]]; then
            echo "Error downloading "
            exit 1
        fi

        diff ~/migrate/continue ~/migrate/$i-$offset-test > /dev/null

        if [[ $? -eq 1 ]]; then
            sleep 2
            break;
        fi

        echo -n "Downloading step " $i " (part " $offset ")... "
        URL= "http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i&offset=$offset" 
        wget $URL -O ~/migrate/$i-$offset.sql.gz 2>/dev/null
        echo "Done"

        offset=$(expr $offset + 1)

        sleep 2
    done
done
