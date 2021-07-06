CREATE TABLE spellen
(
    spelid SERIAL NOT NULL,
    consoleid BIGINT UNSIGNED NOT NULL,
    naam VARCHAR(255) NOT NULL,
    map VARCHAR(255) NOT NULL,
    developer VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    developerurl VARCHAR(255) NOT NULL,
    publisherurl VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    stemmen INT NOT NULL,
    PRIMARY KEY(spelid),
    FOREIGN KEY(consoleid) REFERENCES consoles(consoleid)
) ENGINE=InnoDB;