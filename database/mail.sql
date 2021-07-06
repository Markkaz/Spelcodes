CREATE TABLE mail
(
    mailid SERIAL NOT NULL,
    titel VARCHAR(255) NOT NULL,
    bericht TEXT NOT NULL,
    email VARCHAR(255),
    gelezen BOOL,
    PRIMARY KEY (mailid)
) ENGINE=InnoDB;