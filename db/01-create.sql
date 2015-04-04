-- Initial DB creation

DROP TABLE IF EXISTS ffnet_items;
DROP TABLE IF EXISTS ffnet_feeds;

CREATE TABLE ffnet_feeds (
	id INT AUTO_INCREMENT NOT NULL,
	url VARCHAR(1000) NOT NULL,
	title VARCHAR(1000) NOT NULL,
	updated_date INT NOT NULL,
	author VARCHAR(1000) NOT NULL,
	PRIMARY KEY (id)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE ffnet_items (
	id INT AUTO_INCREMENT NOT NULL,
	feed INT NOT NULL,
	title VARCHAR(1000) NOT NULL,
        chapter INT NOT NULL,
	url VARCHAR(1000) NOT NULL,
	published_date INT NOT NULL,
	description TEXT,
	
	PRIMARY KEY (id),
	CONSTRAINT fk_feeds FOREIGN KEY (feed)
		REFERENCES ffnet_feeds (id)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


