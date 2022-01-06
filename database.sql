-- Poistaa ja luo tietokannan
drop database if exists n0hite00;
create database n0hite00;

use n0hite00;

-- Luo käyttäjä taulun
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Luo yksi testikäyttäjä ilman salasanan hässiä
insert into users(username,password) value ('testaaja','erittäinsalainen');