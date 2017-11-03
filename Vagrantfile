# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.require_version ">= 1.8.4"

Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-16.04"
  config.vm.network "private_network", ip: "192.168.120.50"
  config.vm.synced_folder "./", "/vagrant", :nfs => true,
    :linux__nfs_options => ['rw','no_subtree_check','no_root_squash','async']
  config.vm.hostname = "app.dev"

  config.vm.provider :virtualbox do |v|
    v.name = "app.dev"
    v.customize [
      "modifyvm", :id,
      "--name", "app.dev",
      "--memory", 2048,
      "--natdnshostresolver1", "on",
      "--cpus", 1,
      "--cableconnected1", "on"
    ]
  end

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.aliases = %w(app.dev)

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "ansible/playbook.yml"
    ansible.limit = 'all'
    ansible.raw_arguments = ["-vv"]
  end
end
