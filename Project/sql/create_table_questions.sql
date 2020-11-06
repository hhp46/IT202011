CREATE TABLE Questions
(
    id        int auto_increment,
    question   varchar(160) not null unique,
    survey_id int,
    primary key (id),
    FOREIGN KEY (survey_id) REFERENCES Survey (id)
)