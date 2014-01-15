#! /bin/sh

rootDBFolder="/Shared Webmaster/Database Exports/"

uploadDirectory="$rootDBFolder`date +'%Y'`/`date +'%m.%d.%y.%H%M'`/"

#./Dropbox-Uploader/dropbox_uploader.sh mkdir "$uploadDirectory"
./dropbox_uploader.sh mkdir "$uploadDirectory"


for arg in "$@"
do
	#./Dropbox-Uploader/dropbox_uploader.sh upload "$arg" "${uploadDirectory}$arg"
	./dropbox_uploader.sh upload "$arg" "${uploadDirectory}$arg"
done
