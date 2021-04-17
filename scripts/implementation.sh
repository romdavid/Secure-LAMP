#!/bin/bash

#Update
sudo apt-get update

#IP Tables
sudo apt install iptables-persistent
sudo iptables -t filter -P INPUT DROP
sudo iptables -A INPUT -p tcp --dport ssh -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 80 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 443 -j ACCEPT
sudo iptables -A INPUT -p icmp --icmp-type echo-request -j ACCEPT
sudo iptables-save > /etc/iptables/rules.v4
