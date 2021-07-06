CREATE TABLE links
(
    linkid SERIAL NOT NULL,
    link VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    incomming INT NOT NULL,
    outcomming int NOT NULL,
    PRIMARY KEY (linkid)
) ENGINE=InnoDB;