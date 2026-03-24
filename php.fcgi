#!/bin/bash

PHP_INI_SCAN_DIR=/home/valpersc/.sh.phpmanager/php81.d
export PHP_INI_SCAN_DIR

DEFAULTPHPINI=/home/valpersc/public_html/test.valpers.com/php81-fcgi.ini
exec /opt/cpanel/ea-php81/root/usr/bin/php-cgi -c ${DEFAULTPHPINI}
