#!/bin/bash

# Updating Box

sudo apt-get -y update

# Installing Apache

sudo apt-get -y install apache2

# Installing PHP and it's dependencies
sudo apt-get -y install php5 libapache2-mod-php5 php5-mcrypt php5-curl

#install vim ... cause why not
sudo apt-get -y install vim nmap

# install cowsay ... cause why not
sudo apt-get -y install cowsay

# Setup root directory for apache to be /var/www/website
sudo sed -i 's/\/var\/www/\/var\/www\/website/g' /etc/apache2/sites-enabled/000-default

# Restart apache server after updating its root directory
sudo service apache2 restart

# Get the ip address to display
IP_ADDRESS=$(ifconfig eth1 | grep 'inet addr' | cut -d ':' -f 2 | cut -d ' ' -f 1)

echo "The cow says that you can access your server by entering this IP in your browser: ${IP_ADDRESS}" | cowsay