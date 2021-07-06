CREATE TABLE spellenview
(
    consoleid BIGINT UNSIGNED NOT NULL,
    spelid BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY(consoleid, spelid),
    FOREIGN KEY (consoleid) REFERENCES consoles(consoleid),
    FOREIGN KEY (spelid) REFERENCES spellen(spelid)
) ENGINE=InnoDB;