# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.configure("2") do |config|
  config.vm.box = "opscode-centos-6.6"
  config.vm.hostname = 'tanakalab-v2-dev-box'

  config.vm.network :private_network, ip: "192.168.44.91"
  config.vm.network "forwarded_port", guest: 22, host: 2210

  config.vm.provision :shell, path: 'bootstrap.sh', :privileged => false, keep_color: true

  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", "3096"]
  end

  config.vm.synced_folder "./", "/vagrant", owner:'nginx', group:'nginx', mount_options:['dmode=777','fmode=777']
end
