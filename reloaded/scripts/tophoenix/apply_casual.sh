#!/bin/bash
for i in `find -iname "*.sql"|awk -F / '{print $2}'|sort -n`; do
	echo -n "Applying script $i... "
	mysql --database=zinolive -u zinolive --password=SQWGnsWWYFh4jLZK <$i
	echo "Done"
done

