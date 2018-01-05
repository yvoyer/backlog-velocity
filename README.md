# Backlog Velocity

Master: [![Build Status](https://travis-ci.org/yvoyer/backlog-velocity.svg?branch=master)](https://travis-ci.org/yvoyer/backlog-velocity)

Project to manage and show estimate for Stories.

Using previous registered Sprint, the application will suggest next Sprint velocities for your team.

## Installation

### Using Vagrant

 * Install [vagrant](http://www.vagrantup.com/downloads.html) (at least 1.8.4) and
 * [VirtualBox](https://www.virtualbox.org/wiki/Downloads) (at least 4.2.16). Then install vagrant plugins using this 

```
    # command :
    vagrant plugin install vagrant-cachier
    vagrant plugin install vagrant-hostmanager
```
 * Run `vagrant up` to setup your environment
 * Open your favorite browser to `app.dev`, and enjoy.

### Using Git (Recommended)

**Requirements**

You must have the following programs installed:

 * [PHP](http://www.php.net/) >= 7.1
 * [Git](http://git-scm.com/)

1. Download the application in the folder `<install-folder>` using: `git clone -b <tag-number> https://github.com/yvoyer/backlog-velocity.git <install-folder>`.
2. Download a [composer.phar](https://getcomposer.org/download/) in your `<install-folder>`.
3. Install the project dependencies using `php composer.phar install` in your `<install-folder>`.
3. Copy the configuration file to your own setup `cp app/config/parameters.yml.dist app/config/parameters.yml` if the file was not created.
4. Configure the database: `./backlog update`

## Configurations

By default, the application runs using **SQLite**.
But you can also use any configuration supported by [Doctrine](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html).

    # SQLite
    parameters:
        # Sqlite database (recommended)
        database_path: '%kernel.project_dir%/var/backlog.sqlite' # %kernel.project_dir% is the root of the project

## Features

The backlog velocity project is a simple app that will suggest Story point for your teams based on previous Sprint.

See [Scrum](https://en.wikipedia.org/wiki/Scrum_%28software_development%29) for more information.

**Basic concepts**

* Project: Placeholder for the Sprints, they are identified with a unique name.
* Person: Any member of your organization that may be assigned to a sprint. Their name must be unique.
* Team: Represents a group of persons. This is to help you filter your persons when assigning persons to a sprint.
* Sprint: Represents an iteration of work that lasts a number of days.

### Sprints

Sprint have the following workflow that must be respected, since errors will be generated when the rules are not respected.

* When creating a Sprint, it must be assign to a specific Project. Upon creation, the Sprint is in a
"Pending" state. While pending, the sprint may be commited sprint members (Person).
* When commiting a Sprint member, you will be required to provide the number of days that this person is available for the sprint.
* When all your team members are commited, you'll be able to start the sprint. Upon starting, the system will suggest you
an estimated number of Story point for the sprint. This suggestion may be replace with your own estimate on start.
* When started, a sprint may not receive new sprint member.
* When you end the sprint, you'll be required to give the actual story points the team performed. This data will help the
system to evaluate next Sprints.

Nice sprinting!!!

## Managing your data

The application provides the following tool to manage your data.

### CLI tool

You can find the available commands by running `php backlog` at the root of your folder.

### Web interface

The Web application is built using the [Symfony framework](http://symfony.com/).
The web server may be run using the [vagrant](#using-vagrant) tool, or using the [Symfony recommendation](http://symfony.com/doc/current/setup/web_server_configuration.html)
