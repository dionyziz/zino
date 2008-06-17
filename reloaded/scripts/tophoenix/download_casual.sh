#!/bin/bash

workpath="/home/dionyziz/migrate"
echo -n "Starting up... "
rm $workpath/test $workpath/continue $workpath/*.gz $workpath/*.sql 2>/dev/null
echo "CONTINUE" > ~/migrate/continue
echo "Done"

standalonesteps=( 0 1 6 8 9 10 11 12 13 14 17 )
for i in "${standalonesteps[@]}"
do
    URL="http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i"
    echo -n "Downloading simple step $i ... "
    wget $URL -O $workpath/$i.sql.gz 2>/dev/null
    if [[ $? -ne 0 ]]; then
        echo "Error downloading from $URL"
        exit 1
    fi
    echo "Done"
done

offsetsteps=( 2 3 4 5 7 15 16 )
for i in "${offsetsteps[@]}"
do
    offset=0
    while true; do
        URL="http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i&testoffset=$offset" 
        wget $URL -O $workpath/test 2>/dev/null
        if [[ $? -ne 0 ]]; then
            echo "Error downloading from $URL"
            exit 1
        fi

        diff $workpath/continue $workpath/test > /dev/null

        if [[ $? -ne 0 ]]; then
            sleep 2
            break;
        fi

        echo -n "Downloading step $i (part $offset)... "
        URL="http://www.zino.gr/scripts/tophoenix/export_casual.php?step=$i&offset=$offset" 
        wget $URL -O $workpath/$i-$offset.sql.gz 2>/dev/null
        if [[ $? -ne 0 ]]; then
            echo "Error downloading from $URL"
            break;
        fi

        echo "Done"

        offset=$(expr $offset + 1)

        sleep 2
    done
done

echo -n "Cleaning up... "
rm $workpath/test $workpath/continue
echo "Done"

echo "Downloaded" `du -sh .|awk '{print $1}'`

echo -n "Decompressing... "
gzip --decompress $workpath/*.gz
echo "Done"

echo -n "Decompressed" `du -sh .|awk '{print $1}'`

