CREATE TABLE stemmen
(
    spelid BIGINT UNSIGNED NOT NULL,
    ip VARCHAR(15) NOT NULL,
    PRIMARY KEY (spelid, ip),
    FOREIGN KEY (spelid) REFERENCES spellen(spelid)
) ENGINE=InnoDB;