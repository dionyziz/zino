# usage: etc/build-tags.sh
find .. -iname "*.php" ! -path "*svn*"|xargs ctags
