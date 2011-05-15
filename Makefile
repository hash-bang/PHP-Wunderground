all: docs/api

docs/api: wunderground.php
	phpdoc -q -f wunderground.php -t docs/api -ti 'PHP-Wunderground API reference' -o HTML:frames:earthli -dn PHP-Wunderground

commit: README
	-git commit -a

push: commit
	git push

clean:
	-rm -r docs/api/*
