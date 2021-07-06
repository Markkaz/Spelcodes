CREATE TABLE users
(
    userid SERIAL NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(64) NOT NULL,
    email VARCHAR(255) NOT NULL,
    ip VARCHAR(15),
    activate BOOL NOT NULL,
    permis INT NOT NULL,
    posts INT NOT NULL,
    datum DATE NOT NULL,
    PRIMARY KEY(userid)
) ENGINE=InnoDB;