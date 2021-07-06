CREATE TABLE nieuwsreacties
(
    reactieid SERIAL NOT NULL,
    nieuwsid BIGINT UNSIGNED NOT NULL,
    userid BIGINT UNSIGNED NOT NULL,
    bericht TEXT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    PRIMARY KEY (reactieid),
    FOREIGN KEY (nieuwsid) REFERENCES nieuws(nieuwsid)
) ENGINE=InnoDB;