#! /bin/sh

./download-databases.sh $1 $2

./upload-databases.sh *.sql.gz
