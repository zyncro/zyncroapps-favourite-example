v2.0 (1 September 2013)
=======================

1.Execute sql file install/create_v2.sql.

2. Change the following properties in config/config.properties:

    [oauth]
    server_uri = https://my.sandbox.zyncro.com
    consumer_key = 5b129d9c4f544d00915a344da5eb7b14
    consumer_secret = dc97821555dc49d59f9c387d206f3d22

    [database]
    hostname = localhost
    database = bmartinez
    username = bmartinez
    password = yaigipha

3. If the environment is On Web, change onweb field to true, on config/config.properties file.

    [environment]
    onweb = true

Also change zapp.IS_ON_WEB to true, on file: js/favorite.js.

    zapp.IS_ON_WEB = true;

4. Link the Js file js/favorite.js

5. Create external API service for mobile access:
PostgreSQL-> db:user-service -> table:externalservice

    service = 'favorite'
    url = '[domain]/zyncroapps/v2/favorite/favorite/external'
    
Migration of user favorites from Favorite Groups to Favorite
=============================================================
1. Execute this query after Favorite has been installed

INSERT INTO favorite (id_owner, id_favorite, category, creation_date, TYPE, order_pos)
SELECT id_owner, id_favorite, TYPE, creation_date, category, order_pos
FROM zyncroapps.favoriteGroups;