-- Create a database named roundcube 
-- for the roundcube service
CREATE DATABASE roundcube;

-- Create a user with all the permisions
-- on the roundcube database for the roundcube
-- service
CREATE USER 'roundcube'@'%' IDENTIFIED BY 'roundcube';
GRANT ALL PRIVILEGES ON roundcube.* TO 'roundcube'@'%';
FLUSH PRIVILEGES;