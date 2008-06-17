#!/bin/bash
find -iname "*.sql"|awk -F / '{print $2}'|sort -n|xargs cat|mysql --database=zinophoenix -u zinophoenix --password=rm99fixed

