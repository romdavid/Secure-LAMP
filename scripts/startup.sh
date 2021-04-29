#!/bin/bash

sudo rsync -av pros.conf /etc/apache2/sites-available/pros.conf
sudo rsync -av pros.conf /etc/apache2/sites-enabled/pros.conf

sudo service apache2 reload
