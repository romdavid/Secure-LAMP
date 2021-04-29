#!/bin/bash

#Update Package Manager
sudo apt-get update


#PHP
sudo apt install php libapache2-mod-php php-mysql


#Apache
sudo apt install apache2
#Setup SSL
sudo a2enmod ssl
sudo systemctl restart apache2
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt
#Setup Apache Config
sudo rsync -av pros.conf        /etc/apache2/sites-available/
sudo rsync -av default-ssl.conf /etc/apache2/sites-available/
sudo a2ensite pros.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2


# MySQL
sudo apt install mysql-server
sudo mysql_secure_installation

#Email
sudo apt-get install ssmtp
#Install dependencies for phpmailer
cd ../backend/
sudo apt install composer
sudo mv -v composer.json vendor/
sudo mv -v composer.lock vendor/
cd vendor/
composer require phpmailer/phpmailer
