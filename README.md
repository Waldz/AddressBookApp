# AddressBookApp

Introduction
------------
Address book with category multiple levels.

This is a simple Web Application implemented with pure PHP, to show code separation patterns.


Installation
------------
Step 1. Configure project, database:

```sh
cp config/autoload/local.php.dist config/autoload/local.php
vim config/autoload/local.php
```

Step 2. Download and install composer to your computer (read howto at https://getcomposer.org/doc/)

```sh
curl -s https://getcomposer.org/installer | php --
mv composer.phar bin/composer
chmod +x bin/composer
```

Step 3. Or manually invoke the shipped `bin/composer`:

```sh
# The `self-update` directive is to download newest up-to-date `composer`
bin/composer self-update
# This install all application required libraries for first time
bin/composer install
```

Step 4. Import database data
```sh
bin/migrate
bin/fixtures demo
```


Virtual Host
------------
Step1. Create fake domain in hosts file

Unix:
```sh
sudo vim /etc/hosts
127.0.0.1 contacts.localhost
```

Windows:
```sh
notepad.exe C:\Windows\System32\drivers\etc\hosts
127.0.0.1 contacts.localhost
```

Step2. Add virtual host and restart Web server
```sh
cp config/nginx_site.conf /etc/init.d/nginx/sites-available/contacts.localhost
ln -s /etc/init.d/nginx/sites-available/contacts.localhost /etc/init.d/nginx/sites-enabled/contacts.localhos
/etc/init.d/nginx reload
```

Step. Woolia! http://contacts.localhost/


Update JS dependencies
------------
Step1. Install Bower
```sh
sudo npm install -g bower
sudo npm install -g bower-installer
```

Step2. If You have Bower
```sh
bower install
```

Step3. Install one dependency
```sh
bower install query --save
```
