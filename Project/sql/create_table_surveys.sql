CREATE TABLE Survey
(
    id          int auto_increment,
    title       varchar(30) not null unique,
    description TEXT,
    category TEXT, -- Address 0, College 1, Professor 2, Favorites 3, Family 4, Sports 5, Other 6
    visibility  int, -- Draft 0, Private 1, Public 2
    created TIMESTAMP default CURRENT_TIMESTAMP,
    modified TIMESTAMP default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    user_id int,
    primary key (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
)