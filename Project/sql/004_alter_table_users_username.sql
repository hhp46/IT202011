
ALTER TABLE Users
    ADD COLUMN username varchar(60) default '';
ADD CONSTRAINT UC_Users UNIQUE (username)