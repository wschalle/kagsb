KAG Server Browser
==================

This project is a KAG server browser that includes a buddy list, and the beginnings of a chat system.

I have no time to continue or maintain this project, so I have released it under a BSD license.

Please feel free to use any of my code for other KAG projects. 

If someone wishes to continue this as an open source project, I would be happy to add maintainers to this repository and keep it open.

# Install #

1. Place the files on a webserver with PHP 5.3+, MySQL, and node.js.
2. Copy config.php.dist and doctrine_config.php.dist to config.php and doctrine_config.php.
3. Set the config options in those two files appropriately.
4. Create the MySQL database and run create_schema.sql on it.
5. Set up a cron job to run updateServerData.php every two to five minutes to do the server data updates.
6. Configure the notification server as outlined in notification/Readme.md

Enjoy!
