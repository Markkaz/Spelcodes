CREATE TABLE forum_categories
(
    cat_id SERIAL NOT NULL,
    cat_titel VARCHAR(255) NOT NULL,
    cat_order INT NOT NULL,
    PRIMARY KEY (cat_id)
) ENGINE=InnoDB;