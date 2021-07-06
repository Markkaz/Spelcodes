CREATE TABLE spellenhulp
(
    spelid BIGINT UNSIGNED NOT NULL,
    topicid BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (spelid, topicid),
    FOREIGN KEY (spelid) REFERENCES spellen(spelid),
    FOREIGN KEY (topicid) REFERENCES topics(topicid)
) ENGINE=InnoDB;