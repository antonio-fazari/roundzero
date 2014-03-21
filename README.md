Honestea
========

Installation:
-------------

    curl -s https://getcomposer.org/installer | php
    php composer.phar install

Edit db config in bootstrap.php (todo: environment specific settings).

    php vendor/bin/doctrine orm:schema-tool:create
