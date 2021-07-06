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