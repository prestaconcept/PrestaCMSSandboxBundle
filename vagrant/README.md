Vagrant Presta CMS Sandbox
=============

This VM has been created with [Puphpet](https://puphpet.com/)

# Description

The box is Ubuntu Server Precise 64 bit 12.04.2 built with VirtualBox.
Guest Additions: Virtualbox 4.2.10.

(http://puppet-vagrant-boxes.puppetlabs.com/)

Provisioning with Puppet.

This configuration includes following software:

* PHP 5.4
* MySql 5.5
* Git 1.7.9.5
* Apache 2.2.22
* Xdebug
* Curl
* Composer
* Vim

You can find all details configuration from the project root folder on vagrant/puppet/common.yml

# Usage

You need only to run

```
cd vagrant
vagrant up
```

You need to add entries into /etc/hosts file at host machine.

```
172.22.22.23 dev-prestacms-sandbox.fr
172.22.22.23 dev-prestacms-sandbox.com
```

From now you should be able to access your sandbox PrestaCMS project at host machine under http://dev-prestacms-sandbox.com/  and http://dev-prestacms-sandbox.fr/ addresses.