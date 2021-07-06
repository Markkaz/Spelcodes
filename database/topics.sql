CREATE TABLE topics
(
    topicid SERIAL NOT NULL,
    userid BIGINT UNSIGNED NOT NULL,
    titel VARCHAR(255) NOT NULL,
    bericht TEXT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    PRIMARY KEY (topicid),
    FOREIGN KEY (userid) REFERENCES users(userid)
) ENGINE=InnoDB;