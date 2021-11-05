CREATE TABLE `user` (
    `user_id`       int NOT NULL AUTO_INCREMENT,
    `username`      varchar(64) NOT NULL,
    `password_hash` binary(32) NOT NULL, -- SHA-256 produces a 32-byte digest
    `salt`          binary(16) NOT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;
