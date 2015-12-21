USE WGTOTW;

DROP TABLE IF EXISTS wgtotw_comment2answer;
DROP TABLE IF EXISTS wgtotw_comment2question;
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
	password char(255),
    url varchar(80),
    activityPoints integer,
    isAdmin bit,
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
    userId integer not null,
    foreign key (userId) references wgtotw_user(id)
);

CREATE TABLE wgtotw_comment2question
(
	idQuestion integer not null,
    idComment integer not null,
    foreign key (idQuestion) references wgtotw_question(id),
    foreign key (idComment) references wgtotw_comment(id),
    primary key (idQuestion, idComment)
);

CREATE TABLE wgtotw_comment2answer
(
	idAnswer integer not null,
    idComment integer not null,
    foreign key (idAnswer) references wgtotw_answer(id),
    foreign key (idComment) references wgtotw_comment(id),
    primary key (idAnswer, idComment)
);