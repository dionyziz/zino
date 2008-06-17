#!/bin/bash
for i in `find -iname "*.sql"|awk -F / '{print $2}'|sort -n`; do
    echo -n "Applying script $i... "
    mysql --database=zinophoenix -u zinophoenix --password=rm99fixed <$i
    echo "Done"
done

