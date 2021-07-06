CREATE TABLE berichten
(
    berichtid SERIAL NOT NULL,
    topicid BIGINT UNSIGNED NOT NULL,
    userid BIGINT UNSIGNED NOT NULL,
    bericht TEXT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    PRIMARY KEY (berichtid),
    FOREIGN KEY (topicid) REFERENCES topics(topicid),
    FOREIGN KEY (userid) REFERENCES users(userid)
) ENGINE=InnoDB;