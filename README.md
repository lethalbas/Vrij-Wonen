# Vrij-Wonen
Vrij Wonen CRUD - Schoolopdracht ROC MN


Default admin login | Admin1:Admin01!
Default moderator login | User1:User01!


Setup:

1 - Import mysql db script vrijwonen.sql to phpmyadmin

2 - Insert your db login info in /app/util/db_connection_util.php

3 - Add htdocs and app folders to the desired location in the file system

4 - Set htdocs folder as the apache webroot

5 - (optional) in case your shared hosting provider does not name the document root folder "/htdocs" you will have to replace refferences to "/htdocs" to the desired document root folder in "/app/utils/file_handler_util.php" for the application to function properly


Note: when testing on a local environment the Gravatar intergration (specifically the default images) will probably not function properly, this is due to their api and only fixable by running the application on an environment which is accesible to the public internet. It isn't a game breaking bug but it is something to be aware of.


Librarys used:

1 - Bootstrap

2 - jQuery

3 - jQuery select2

4 - jQuery redirect

5 - jQuery toast notifications

6 - jQuery print

7 - jQuery confirm
