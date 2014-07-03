Backlog Velocity
================

Project to manage and show estimate for Stories.

Using previous registered Sprint, the application will suggest next Sprint velocities for your team.

Installation
------------

**Requirements**

You must have the following programs installed:

 * [PHP](http://www.php.net/)
 * [Git](http://git-scm.com/)

**Using Git (Recommended)**

1. Download the application in the folder `<folder/path>` using: `git clone https://github.com/yvoyer/backlog-velocity.git <folder/path> && git checkout 1.0.0`.
2. Once the project is on your machine, you can launch `php composer.phar install`.
3. Copy the configuration file to your own setup `cp config.yml.template config.yml`

Features
--------

**Basic concepts**

* Person: Represents a person identified by a unique name.
* Team: Represents a group of persons who MAY work on a Sprint.
* Team members: Represents a person that is part of a team.
* Sprint: Represents an iteration that can last a number of days. A sprint is linked to a team, and is unique for the team based on its name.
* Sprint member: Represents a team member committed to a sprint for a number of days.

You can find the available commands by running `php backlog` at the root of your folder.
