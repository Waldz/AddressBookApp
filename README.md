# AddressBookApp
Address book with category multiple levels

Introduction
------------
This is a simple Address Book Web Application implemented with pure PHP, to show code separation patterns.


Installation
------------

Deploy application
----------------------------
Step 1. Configure project, database:
    cp config/autoload/local.php.dist config/autoload/local.php
    vim config/autoload/local.php

Step 2. Download and install composer to your computer (read howto at https://getcomposer.org/doc/)
    curl -s https://getcomposer.org/installer | php --
    mv composer.phar bin/composer
    chmod +x bin/composer

Step 3. Or manually invoke the shipped `bin/composer`:
    # The `self-update` directive is to download newest up-to-date `composer`
    bin/composer self-update
    # This install all application required libraries for first time
    bin/composer install

Step 4. Import database data
    bin/migrate


Virtual Host
------------
Step1. Create fake domain in hosts file
Unix:
    sudo vim /etc/hosts
    127.0.0.1 contacts.localhost
Windows:
    notepad.exe C:\Windows\System32\drivers\etc\hosts
    127.0.0.1 contacts.localhost

Step2. Add virtual host and restart Web server
    cp config/nginx_site.conf /etc/init.d/nginx/sites-available/contacts.localhost
    ln -s /etc/init.d/nginx/sites-available/contacts.localhost /etc/init.d/nginx/sites-enabled/contacts.localhos
    /etc/init.d/nginx reload

Step. Woolia! http://contacts.localhost/
