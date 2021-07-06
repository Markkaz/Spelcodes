CREATE TABLE users
(
    userid SERIAL NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(41) NOT NULL,
    email VARCHAR(255) NOT NULL,
    ip VARCHAR(15),
    activate BOOL NOT NULL,
    permis INT NOT NULL,
    posts INT NOT NULL,
    datum DATE NOT NULL,
    PRIMARY KEY(userid)
) ENGINE=InnoDB;

CREATE TABLE nieuws
(
    nieuwsid SERIAL NOT NULL,
    userid BIGINT UNSIGNED NOT NULL,
    titel VARCHAR(255) NOT NULL,
    bericht TEXT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    PRIMARY KEY(nieuwsid),
    FOREIGN KEY (userid) REFERENCES users(userid)
) ENGINE=InnoDB;

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

CREATE TABLE consoles
(
    consoleid SERIAL NOT NULL,
    naam VARCHAR(255) NOT NULL,
    PRIMARY KEY(consoleid)
) ENGINE=InnoDB;

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

CREATE TABLE spellenview
(
    consoleid BIGINT UNSIGNED NOT NULL,
    spelid BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY(consoleid, spelid),
    FOREIGN KEY (consoleid) REFERENCES consoles(consoleid),
    FOREIGN KEY (spelid) REFERENCES spellen(spelid)
) ENGINE=InnoDB;

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

CREATE TABLE spellenhulp
(
    spelid BIGINT UNSIGNED NOT NULL,
    topicid BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (spelid, topicid),
    FOREIGN KEY (spelid) REFERENCES spellen(spelid),
    FOREIGN KEY (topicid) REFERENCES topics(topicid)
) ENGINE=InnoDB;

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

CREATE TABLE stemmen
(
    spelid BIGINT UNSIGNED NOT NULL,
    ip VARCHAR(15) NOT NULL,
    PRIMARY KEY (spelid, ip),
    FOREIGN KEY (spelid) REFERENCES spellen(spelid)
) ENGINE=InnoDB;

CREATE TABLE links
(
    linkid SERIAL NOT NULL,
    link VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    incomming INT NOT NULL,
    outcomming int NOT NULL,
    PRIMARY KEY (linkid)
) ENGINE=InnoDB;

CREATE TABLE mail
(
    mailid SERIAL NOT NULL,
    titel VARCHAR(255) NOT NULL,
    bericht TEXT NOT NULL,
    email VARCHAR(255),
    gelezen BOOL,
    PRIMARY KEY (mailid)
) ENGINE=InnoDB;

CREATE TABLE forum_categories
(
    cat_id SERIAL NOT NULL,
    cat_titel VARCHAR(255) NOT NULL,
    cat_order INT NOT NULL,
    PRIMARY KEY (cat_id)
) ENGINE=InnoDB;

CREATE TABLE  forum_forums
(
    forum_id SERIAL NOT NULL,
    cat_id BIGINT UNSIGNED NOT NULL,
    forum_titel VARCHAR(255) NOT NULL,
    forum_text TEXT NOT NULL,
    last_post BIGINT UNSIGNED,
    last_post_time DATETIME,
    PRIMARY KEY (forum_id),
    FOREIGN KEY (cat_id) REFERENCES forum_categories(cat_id)
) ENGINE=InnoDB;

CREATE TABLE forum_topics
(
    topic_id SERIAL NOT NULL,
    forum_id BIGINT UNSIGNED NOT NULL,
    topic_titel VARCHAR(255),
    topic_poster BIGINT UNSIGNED NOT NULL,
    topic_time DATETIME NOT NULL,
    topic_replies INT NOT NULL,
    topic_status BOOL NOT NULL,
    topic_views INT NOT NULL,
    last_post BIGINT UNSIGNED NOT NULL,
    last_post_time DATETIME NOT NULL,
    PRIMARY KEY (topic_id),
    FOREIGN KEY (forum_id) REFERENCES forum_forums(forum_id),
    FOREIGN KEY (topic_poster) REFERENCES users(userid)
) ENGINE=InnoDB;

CREATE TABLE forum_posts
(
    post_id SERIAL NOT NULL,
    topic_id BIGINT UNSIGNED NOT NULL,
    post_poster BIGINT UNSIGNED NOT NULL,
    post_time DATETIME NOT NULL,
    post_titel VARCHAR(255) NOT NULL,
    post_text TEXT NOT NULL,
    PRIMARY KEY (post_id),
    FOREIGN KEY (topic_id) REFERENCES forum_topics(topic_id),
    FOREIGN KEY (post_poster) REFERENCES users(userid)
) ENGINE=InnoDB;