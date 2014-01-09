#!/bin/bash

OS=$(/bin/bash /vagrant/puppet/shell/os-detect.sh ID)
CODENAME=$(/bin/bash /vagrant/puppet/shell/os-detect.sh CODENAME)

if [[ ! -d /.opesho-puppet-stuff ]]; then
    cat /vagrant/puppet/shell/promo.txt
    mkdir /.opesho-puppet-stuff
    echo "Created directory /.opesho-puppet-stuff"
fi

if [[ ! -f /.opesho-puppet-stuff/initial-setup-repo-update ]]; then
    if [ "$OS" == 'debian' ] || [ "$OS" == 'ubuntu' ]; then
        echo "Running initial-setup apt-get update"
        apt-get update >/dev/null
        touch /.opesho-puppet-stuff/initial-setup-repo-update
        echo "Finished running initial-setup apt-get update"
    elif [[ "$OS" == 'centos' ]]; then
        echo "Running initial-setup yum update"
        yum update -y >/dev/null
        echo "Finished running initial-setup yum update"

        echo "Installing basic development tools (CentOS)"
        yum -y groupinstall "Development Tools" >/dev/null
        echo "Finished installing basic development tools (CentOS)"
        touch /.opesho-puppet-stuff/initial-setup-repo-update
    fi
fi

if [[ "$OS" == 'ubuntu' && ("$CODENAME" == 'lucid' || "$CODENAME" == 'precise') && ! -f /.opesho-puppet-stuff/ubuntu-required-libraries ]]; then
    echo 'Installing basic curl packages (Ubuntu only)'
    apt-get install -y libcurl3 libcurl4-gnutls-dev >/dev/null
    echo 'Finished installing basic curl packages (Ubuntu only)'

    touch /.opesho-puppet-stuff/ubuntu-required-libraries
fi
