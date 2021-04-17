#!/bin/bash

#Update System
sudo apt-get update


#PHP 
sudo apt install php libapache2-mod-php php-mysql


#Apache
sudo apt install apache2
#Setup SSL
sudo a2enmod ssl
sudo systemctl restart apache2
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/apache-selfsigned.key -out /etc/apache2/ssl/apache-selfsigned.crt
#Setup Apache Config
sudo rsync -av --exclude installation.sh . /etc/apache2/sites-available/
sudo a2ensite pros.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2


# MySQL
sudo apt install mysql-server
sudo mysql_secure_installation
p=$(awk -F "=" '/password/ {print $2}' ../private/config.ini)
sudo mysql --execute="ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY $p; FLUSH PRIVILEGES; CREATE DATABASE $database; create database website; use website; create table info (id INT AUTO_INCREMENT PRIMARY KEY,first VARCHAR(30) NOT NULL,last VARCHAR(30) NOT NULL,birth DATE NOT NULL,logins INT NOT NULL,prev_login DATE,question1 VARCHAR(80) NOT NULL,question2 VARCHAR(80) NOT NULL,answer1 VARCHAR(40) NOT NULL,answer2 VARCHAR(40) NOT NULL); create table login (id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(20) NOT NULL,password VARCHAR(40) NOT NULL,activated int NOT NULL,token VARCHAR(40),email VARCHAR(50) NOT NULL);"


#Email
sudo apt-get install ssmtp










