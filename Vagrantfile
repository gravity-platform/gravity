# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # Every Vagrant virtual environment requires a box to build off of.
  # You might want to create symlink called base to the box you are using.
  # This Vagrant file uses the "base" box due to it being the de facto
  # default.
  config.vm.box = "base"

  # The url from where the 'config.vm.box' box will be fetched if it
  # doesn't already exist on the user's system.
  # This public box contains a current centos6 as well as puppet 3.
  # We might consider replacing this with a box of our own that
  # contains even less features in the future.
  config.vm.box_url = "http://developer.nrel.gov/downloads/vagrant-boxes/CentOS-6.4-x86_64-v20130731.box"

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network :forwarded_port, guest: 80, host: 8080

  # If true, then any SSH connections made will enable agent forwarding.
  # Default value: false
  config.ssh.forward_agent = true

  # Enable provisioning with Puppet stand alone.
  config.vm.provision :puppet do |puppet|
    # this manifest comes from the puppet-gravity module
    puppet.manifest_file  = "modules/gravity/tests/full.pp"
    # the modules are installed in this path by librarian-puppet
    puppet.module_path = "modules"
    # not needed by us but needs to point to a valid dir (cwd is valid)
    puppet.manifests_path = ""
  end
end
