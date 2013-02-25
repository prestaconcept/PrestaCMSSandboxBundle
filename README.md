Presta CMS Sandbox [![Build Status](https://secure.travis-ci.org/prestaconcept/prestacms-sandbox.png?branch=master)](http://travis-ci.org/prestaconcept/prestacms-sandbox)
=============


## Overview ##

This is a sandbox to test the [PrestaCMSCorebundle](https://github.com/prestaconcept/PrestaCMSCoreBundle)

It is based on :
* [**Symfony Standard edition**](https://github.com/symfony/symfony-standard)
* [**Symfony CMF**](http://symfony.com/doc/master/cmf/index.html)

[WIP] This project is still under heaving development and is not yet ready to use in production.
PrestaConcept is currently developing it for their new website.

If you want to have some informations about the projet progession you can register to our [google group](https://groups.google.com/forum/?hl=fr&fromgroups#!forum/prestacms-devs)

### Front website with custom theming ###

#### English version ####
![PrestaCMS Dashboard](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/frontend-english.png)


#### French version ####
![PrestaCMS Dashboard](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/frontend-french.png)


### Integrated with Sonata Admin ###
![PrestaCMS Dashboard](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-dashboard.png)

### Manage multiple front website from the same backend ###

#### Listing ####
![Website listing](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-website-list.png)

#### Edition ####
![Website edition](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-website-edit.png)

### Themes administration ###

#### Listing ####
![Theme listing](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-theme-list.png)

#### Edition for a website ####
![Theme edition](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-theme-edit.png)

### Content administration ###
![Page edition](https://raw.github.com/prestaconcept/prestacms-sandbox/master/app/Resources/docs/assets/backend-page-edit.png)

## Features ##

- [x] Multiple website
- [x] Custom theming
- [x] Page edition
- [x] WYSIWYG integration

**Todo**
- [ ] Demonstration multiple website
- [ ] Add media block
- [ ] Add a nice documentation

## Installing PrestaCMS Sandbox ##

### Get the code ###

As Symfony uses [Composer](http://getcomposer.org/) to manage its dependencies, the recommended way to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on http://getcomposer.org/ or just run the following command:

   curl -s http://getcomposer.org/installer | php

Then, use the `create-project` command to generate a new Symfony application:

   php composer.phar create-project presta/cms-sandbox path/to/install/prestacms-sandbox -s dev

Composer will install Symfony and all its dependencies under the
`path/to/install` directory.

### Create the database ###

Sandbox is configured to work on SQLite. Database file will be create it app/database/app.sqlite.

    app/console doctrine:database:create
    app/console doctrine:schema:create
    chmod 777 app/database app/logs app/cache
    chmod 777 app/database/*
    app/console doctrine:phpcr:init:dbal
    app/console doctrine:phpcr:register-system-node-types
    app/console doctrine:phpcr:fixtures:load --no-interaction

---

If you want more documentation about settings up Doctrine PHPCR-ODM with Jackrabbit or Midgard, have a look at
the [specific documentation](http://symfony.com/doc/master/cmf/tutorials/installing-configuring-doctrine-phpcr-odm.html) or
the [cmf-sandbox set-up]()

### Virtual host ###

As PrestaCMS depends on host configuration, you have to set up virtual hosts for the sandbox.

**/etc/hosts**

    127.0.0.1 www.prestacms-sandbox.fr.local prestacms-sandbox.fr.local
    127.0.0.1 www.prestacms-sandbox.en.local prestacms-sandbox.en.local

**vhosts.conf**

    #PrestaCMS Sandbox
    <VirtualHost 127.0.0.1:80>
        DocumentRoot /home/nbastien/Workspace/RetD/prestacms-sandbox/web

        Servername www.prestacms-sandbox.fr.local
        Serveralias www.prestacms-sandbox.en.local

        ErrorLog "logs/error_log"
        CustomLog "logs/access_log" common
    </VirtualHost>


## Ask for help ##

If you need help about this project you can [post a message on our google group](https://groups.google.com/forum/?hl=fr&fromgroups#!forum/prestacms-devs)


---

*This project is supported by [PrestaConcept](http://www.prestaconcept.net)*

**Usefull links**
 * [**PrestaCMSCorebundle Documentation**](https://github.com/prestaconcept/PrestaCMSCoreBundle)
 * [**PrestaConcept Bundles**](https://github.com/prestaconcept)
 * [**Symfony CMF Documentation**](http://symfony.com/doc/master/cmf/index.html)

