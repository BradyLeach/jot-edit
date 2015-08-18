

CREATE DATABASE `gngr` CHARACTER SET utf8 COLLATE utf8_unicode_ci;


CREATE USER 'webmaster'@'localhost' IDENTIFIED BY 'FSuBATyhmTAhT3XN';
    GRANT ALL ON `gngr`.* TO 'webmaster'@'localhost';
    FLUSH PRIVILEGES;


