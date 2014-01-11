CREATE TABLE sb_appstatus (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, updatedServers INT NOT NULL, newServers INT NOT NULL, updatedPlayers INT NOT NULL, newPlayers INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE sb_buddy (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, player_id INT DEFAULT NULL, confirmed TINYINT(1) NOT NULL, createDate DATETIME NOT NULL, updateDate DATETIME DEFAULT NULL, INDEX IDX_7E89DEB0A76ED395 (user_id), INDEX IDX_7E89DEB099E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE sb_player (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, updateDate DATETIME NOT NULL, createDate DATETIME NOT NULL, active TINYINT(1) NOT NULL, banned TINYINT(1) NOT NULL, gold TINYINT(1) NOT NULL, role INT NOT NULL, UNIQUE INDEX UNIQ_41AF0EE0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE sb_player_server (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, player_id INT DEFAULT NULL, createDate DATETIME NOT NULL, updateDate DATETIME DEFAULT NULL, samples INT DEFAULT NULL, INDEX IDX_4B2BC20D1844E6B7 (server_id), INDEX IDX_4B2BC20D99E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE sb_server (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(255) NOT NULL, firstSeen DATETIME NOT NULL, lastUpdate DATETIME NOT NULL, IPv4Address VARCHAR(255) DEFAULT NULL, IPv6Address VARCHAR(255) DEFAULT NULL, Port INT NOT NULL, Name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
CREATE TABLE sb_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(60) NOT NULL, createDate DATETIME NOT NULL, updateDate DATETIME NOT NULL, email VARCHAR(254) DEFAULT NULL, playerKagName VARCHAR(255) DEFAULT NULL, playerForumId INT DEFAULT NULL, playerVerified TINYINT(1) NOT NULL, playerVerificationToken VARCHAR(40) DEFAULT NULL, lastSeen DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB;
ALTER TABLE sb_buddy ADD CONSTRAINT FK_7E89DEB0A76ED395 FOREIGN KEY (user_id) REFERENCES sb_user (id);
ALTER TABLE sb_buddy ADD CONSTRAINT FK_7E89DEB099E6F5DF FOREIGN KEY (player_id) REFERENCES sb_player (id);
ALTER TABLE sb_player ADD CONSTRAINT FK_41AF0EE0A76ED395 FOREIGN KEY (user_id) REFERENCES sb_user (id);
ALTER TABLE sb_player_server ADD CONSTRAINT FK_4B2BC20D1844E6B7 FOREIGN KEY (server_id) REFERENCES sb_server (id);
ALTER TABLE sb_player_server ADD CONSTRAINT FK_4B2BC20D99E6F5DF FOREIGN KEY (player_id) REFERENCES sb_player (id);
