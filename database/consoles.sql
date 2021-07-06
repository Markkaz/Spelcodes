CREATE TABLE consoles
(
    consoleid SERIAL NOT NULL,
    naam VARCHAR(255) NOT NULL,
    PRIMARY KEY(consoleid)
) ENGINE=InnoDB;