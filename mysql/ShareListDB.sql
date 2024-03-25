-- Creazione DB
DROP SCHEMA IF EXISTS ShareListDB;
CREATE SCHEMA ShareListDB;
USE ShareListDB;

-- Creazione Tabelle
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    first_name  VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    ordinary_lists INT UNSIGNED DEFAULT 0,
    guests_lists INT UNSIGNED DEFAULT 0,
    party_lists INT UNSIGNED DEFAULT 0,
    trip_lists INT UNSIGNED DEFAULT 0,
    holiday_lists INT UNSIGNED DEFAULT 0,
    shopping_done INT UNSIGNED DEFAULT 0,
    elements_added INT UNSIGNED DEFAULT 0,
    
    PRIMARY KEY (username)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS lists;
CREATE TABLE IF NOT EXISTS lists (
    list_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    list_name VARCHAR(255),
    creator VARCHAR(255) NOT NULL,
    purpose VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    blocked VARCHAR(255) DEFAULT NULL,
    
    FOREIGN KEY (blocked) REFERENCES users(username),
    PRIMARY KEY (list_id)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS participants;
CREATE TABLE IF NOT EXISTS participants (
    user VARCHAR(255) NOT NULL,
    list INT UNSIGNED AUTO_INCREMENT NOT NULL,
    
    FOREIGN KEY (user) REFERENCES users(username),
    FOREIGN KEY (list) REFERENCES lists(list_id),
    PRIMARY KEY (user, list)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS elements;
CREATE TABLE IF NOT EXISTS elements (
    elem_name VARCHAR(255) NOT NULL,
    list INT UNSIGNED AUTO_INCREMENT NOT NULL,
    type VARCHAR(255) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    
    FOREIGN KEY (list) REFERENCES lists(list_id),
    PRIMARY KEY (elem_name, list)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS achievements;
CREATE TABLE IF NOT EXISTS achievements (
    trophy_id INT UNSIGNED NOT NULL,
    user VARCHAR(255) NOT NULL,
    date DATE NOT NULL,

    FOREIGN KEY (user) REFERENCES users(username),
    PRIMARY KEY (trophy_id, user)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
