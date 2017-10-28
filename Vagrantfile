# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.require_version ">= 1.8.4"

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.network "private_network", ip: "192.168.100.51"
  config.vm.synced_folder "./", "/vagrant", :nfs => true,
    :linux__nfs_options => ['rw','no_subtree_check','no_root_squash','async']
  config.vm.hostname = "backlog.dev"

  config.vm.provider :virtualbox do |v|
  	v.name = "backlog.dev"
  	v.customize [
  	  "modifyvm", :id,
  	  "--name", "backlog.dev",
  	  "--memory", 2048,
  	  "--natdnshostresolver1", "on",
  	  "--cpus", 1,
  	]
  end

 # if Vagrant.has_plugin?("vagrant-hostmanager")
 #   config.hostmanager.enabled = true
 #   config.hostmanager.manage_host = true
 #   config.hostmanager.aliases = %w(back.prome.dev front.prome.dev back.prome.prod front.prome.prod prome.prod)
 # end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "ansible/playbook.yml"
    ansible.limit = 'all'
    ansible.raw_arguments = ["-vv"]
  end
end
