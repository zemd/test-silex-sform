Vagrant.configure("2") do |config|
  config.vm.box = "elance-php-mongo-ubuntu-precise12042-x64-vbox43"
  #config.vm.box_url = "http://box.puphpet.com/ubuntu-precise12042-x64-vbox43.box"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  config.vm.network "private_network", ip: "192.168.56.101"
  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.synced_folder "./", "/var/www", id: "vagrant-root", :nfs => false

  config.vm.usable_port_range = (2200..2250)
  config.vm.provider :virtualbox do |virtualbox|
    virtualbox.customize ["modifyvm", :id, "--name", "elance-php-mongo-ubuntu-precise12042-x64-vbox43"]
    virtualbox.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    virtualbox.customize ["modifyvm", :id, "--memory", "1024"]
    virtualbox.customize ["setextradata", :id, "--VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
  end


  config.vm.provision :shell, :path => "puppet/shell/bootstrap.sh"
  config.vm.provision :shell, :path => "puppet/shell/install-puppet.sh"
  config.vm.provision :shell, :path => "puppet/shell/librarian-puppet-vagrant.sh"
  config.vm.provision :puppet do |puppet|
    puppet.facter = {
      "ssh_username" => "vagrant"
    }

    puppet.manifests_path = "puppet/puppet/manifests"
    puppet.options = ["--verbose", "--hiera_config /vagrant/puppet/hiera.yaml", "--parser future"]
  end




  config.ssh.username = "vagrant"

  #config.ssh.shell = "bash -l"
  config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

  config.ssh.keep_alive = true
  config.ssh.forward_agent = false
  config.ssh.forward_x11 = false
  config.vagrant.host = :detect
end

