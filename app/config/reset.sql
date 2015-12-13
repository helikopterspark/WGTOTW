USE WGTOTW;

DROP TABLE IF EXISTS wgtotw_comment;
DROP TABLE IF EXISTS wgtotw_tag2question;
DROP TABLE IF EXISTS wgtotw_tag;
DROP TABLE IF EXISTS wgtotw_answer;
DROP TABLE IF EXISTS wgtotw_question;
DROP TABLE IF EXISTS wgtotw_user;

CREATE TABLE wgtotw_user
(
	id integer primary key not null auto_increment,
    acronym varchar(20) unique not null,
	email varchar(80),
	name varchar(80),
	password char(32),
    activityPoints integer,
	created datetime,
	updated datetime,
	deleted datetime,
	active datetime
);

CREATE TABLE wgtotw_question
(
	id integer primary key not null auto_increment,
    title varchar(80),
    data text,
    created datetime,
    updated datetime,
    deleted datetime,
    upvotes integer,
    downvotes integer,
    questionUserId integer not null,
    foreign key (questionUserId) references wgtotw_user(id)
);

CREATE TABLE wgtotw_answer
(
	id integer primary key not null auto_increment,
    title varchar(80),
    data text,
    created datetime,
    updated datetime,
    deleted datetime,
    upvotes integer,
    downvotes integer,
    accepted bit,
    answerUserId integer not null,
    questionId integer not null,
    foreign key (answerUserId) references wgtotw_user(id),
    foreign key (questionId) references wgtotw_question(id)
);

CREATE TABLE wgtotw_tag
(
	id integer primary key not null auto_increment,
	name varchar(80) unique,
	description varchar(255),
	created datetime,
	updated datetime,
	deleted datetime
);

CREATE TABLE wgtotw_tag2question
(
	idQuestion integer not null,
	idTag integer not null,
    foreign key (idQuestion) references wgtotw_question(id),
	foreign key (idTag) references wgtotw_tag(id),
	primary key (idQuestion, idTag)
);

CREATE TABLE wgtotw_comment
(
	id integer primary key not null auto_increment,
    content text,
    created datetime,
    updated datetime,
    deleted datetime,
    upvotes integer,
    downvotes integer,
    questionId integer,
    answerId integer,
    userId integer not null,
    foreign key (questionId) references wgtotw_question(id),
    foreign key (answerId) references wgtotw_answer(id),
    foreign key (userId) references wgtotw_user(id)
);