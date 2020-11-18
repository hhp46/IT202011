CREATE TABLE Response
(
    id          int auto_increment,
    survey_id   int,
    question_id int,
    answer_id int,
    modified    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    created     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    user_id     int,
    primary key (id),
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (question_id) REFERENCES Questions (id),
    FOREIGN KEY (survey_id) REFERENCES Survey (id),
    UNIQUE KEY (user_id, question_id, answer_id survey_id)
)