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