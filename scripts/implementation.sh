#!/bin/bash

#Update
sudo apt-get update

#IP Tables
sudo apt install iptables-persistent
sudo iptables -A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
sudo iptables -A INPUT -i lo -j ACCEPT
sudo iptables -A INPUT -p icmp -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 80 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 443 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 22 -j ACCEPT
sudo iptables -P INPUT DROP
sudo iptables-save > /etc/iptables/rules.v4


## Snort Pre-reqs ##
sudo apt-get dist-upgrade -y
sudo dpkg-reconfigure tzdata
mkdir ~/snort_src
cd ~/snort_src
sudo apt-get install -y build-essential autotools-dev libdumbnet-dev libluajit-5.1-dev libpcap-dev \ zlib1g-dev pkg-config libhwloc-dev cmake liblzma-dev openssl libssl-dev cpputest libsqlite3-dev \ libtool uuid-dev git autoconf bison flex libcmocka-dev libnetfilter-queue-dev libunwind-dev \ libmnl-dev ethtool
#safec for runtime bounds checks on certain legacy C-library calls
cd ~/snort_src
wget https://github.com/rurban/safeclib/releases/download/v02092020/libsafec-02092020.tar.gz
tar -xzvf libsafec-02092020.tar.gz
cd libsafec-02092020.0-g6d921f
./configure
make
sudo make install
#Perl Compatible Regular Expressions
cd ~/snort_src/
wget https://ftp.pcre.org/pub/pcre/pcre-8.44.tar.gz
tar -xzvf pcre-8.44.tar.gz
cd pcre-8.44
./configure
make
sudo make install
#gperools 2.8
cd ~/snort_src
wget https://github.com/gperftools/gperftools/releases/download/gperftools-2.8/gperftools-2.8.tar.gz
tar xzvf gperftools-2.8.tar.gz
cd gperftools-2.8
./configure
make
sudo make install
#Ragel
cd ~/snort_src
wget http://www.colm.net/files/ragel/ragel-6.10.tar.gz
tar -xzvf ragel-6.10.tar.gz
cd ragel-6.10
./configure
make
sudo make install
#Boost C++ Libraries:
cd ~/snort_src
wget https://dl.bintray.com/boostorg/release/1.74.0/source/boost_1_74_0.tar.gz
tar -xvzf boost_1_74_0.tar.gz
#Hyperscan
cd ~/snort_src
wget https://github.com/intel/hyperscan/archive/v5.3.0.tar.gz
tar -xvzf v5.3.0.tar.gz
mkdir ~/snort_src/hyperscan-5.3.0-build
cd hyperscan-5.3.0-build/
cmake -DCMAKE_INSTALL_PREFIX=/usr/local -DBOOST_ROOT=~/snort_src/boost_1_74_0/ ../hyperscan-5.3.0
make
sudo make install
#flatbuers
cd ~/snort_src
wget https://github.com/google/flatbuffers/archive/v1.12.0.tar.gz -O flatbuffers-v1.12.0.tar.gz
tar -xzvf flatbuffers-v1.12.0.tar.gz
mkdir flatbuffers-build
cd flatbuffers-build
cmake ../flatbuffers-1.12.0
make
sudo make install
#Snort Data Acquisition library (DAQ)
cd ~/snort_src
wget https://github.com/snort3/libdaq/archive/refs/tags/v3.0.3.tar.gz
tar -xzvf v3.0.3.tar.gz
cd libdaq-3.0.3
./bootstrap
./configure
make
sudo make install
## End of Snort pre-reqs ##

#Snort
cd ~/snort_src
wget https://github.com/snort3/snort3/archive/refs/tags/3.1.4.0.tar.gz
tar -xzvf 3.1.4.0.tar.gz
cd snort3-3.1.4.0
./configure_cmake.sh --prefix=/usr/local --enable-tcmalloc
cd build
make
sudo make install

#Snort testing
snort -c /usr/local/etc/snort/snort.lua

#Adding Snort directories
sudo mkdir /usr/local/etc/rules
sudo mkdir /usr/local/etc/so_rules/
sudo mkdir /usr/local/etc/lists/
sudo touch /usr/local/etc/rules/snort.rules
sudo touch /usr/local/etc/rules/local.rules
sudo touch /usr/local/etc/lists/default.blocklist
sudo mkdir /var/log/snort

#Community Snort Rules: PullPorked
cd ~/snort_src
wget https://github.com/shirkdog/pulledpork/archive/master.tar.gz -O pulledpork-master.tar.gz
tar xzvf pulledpork-master.tar.gz
cd pulledpork-master/
sudo cp pulledpork.pl /usr/local/bin
sudo chmod +x /usr/local/bin/pulledpork.pl
sudo mkdir /usr/local/etc/pulledpork
sudo cp etc/*.conf /usr/local/etc/pulledpork

 ##Change that has to be done manually ##
#sudo vi /usr/local/etc/snort/snort.lua
#ips =
#{
# enable_builtin_rules = true,
# include = RULE_PATH .. "/snort.rules",
# variables = default_variables
#}

## PullPorked configuration: This has to be done manually ##
#sudo vi /usr/local/etc/pulledpork/pulledpork.conf
#Line 19 rule_url=https://www.snort.org/downloads/registered/|snortrules-snapshot-3130.tar.gz|287166295914eb5cca8dcb6a53ad8d8604669b3f
#Line 21 #rule_url=https://snort.org/downloads/community/|community-rules.tar.gz|Community
#Line 72
#rule_path=/usr/local/etc/rules/snort.rules
#Line 87
#local_rules=/usr/local/etc/rules/local.rules
#Line 94
#sid_msg_version=2
#Line 110
#sorule_path=/usr/local/etc/so_rules/
#Line 134
#distro=Ubuntu-18-4
#Line 142
#block_list=/usr/local/etc/lists/default.blocklist
#Line 151
#IPRVersion=/usr/local/etc/lists
#Line 186
#pid_path=/var/log/snort/snort.pid
#Line 209
#ips_policy=security

## This'll run Snort ##
# sudo /usr/local/bin/pulledpork.pl -c /usr/local/etc/pulledpork/pulledpork.conf -l -P -E -H SIGHUP