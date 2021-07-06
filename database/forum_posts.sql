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