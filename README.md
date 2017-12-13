SnowTricks
==========

A Symfony project created on November 16, 2017, 4:19 pm.

Purpose : Creating an community site about snowboard tricks.
As anonymous, you can access :
- a page with the trick list (homepage)
- for each trick, a page to see the details : description, medias (photos and videos) and list of comments
- a page for register as user
- a page for login as user
When connected, you can also :
- add a trick
- update a trick
- add a comment

# Configuration
Symfony 3.3.11
php     7.1.8
MySQL   5.6.35


# Download the project from github on your computer
- within zip format on `https://github.com/caroleduval/Snowtricks`
- via the console :
    `git clone https://github.com/caroleduval/Snowtricks.git`

# Install the projet with the console
- browse to the directory that contains the project.
- Run `composer update` and define your own values when asked.

# Fill the database with tricks and users datas
- Run : `app:initialize-SN`
- open [http://localhost/snowtricks/web/app_dev.php]

It's now OK !