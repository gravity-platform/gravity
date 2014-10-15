
## Install

You will need composer and vagrant and ruby bundler for the installation process.

### Install Prerequisites
````bash
(yum|port|aptitude) install ruby-bundler # (or "sudo gem install bundler")
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
# grab and install a package from https://www.vagrantup.com/downloads
````

### Install vagrant machine
````bash
composer install
vagrant up
````

