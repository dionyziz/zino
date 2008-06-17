#!/bin/bash
for i in `find -iname "*.sql"|awk -F / '{print $2}'|sort -n`; do
    echo $i #|xargs cat|mysql --database=zinophoenix -u zinophoenix --password=rm99fixed
done

