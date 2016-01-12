-- CREATE DATABASE IF NOT EXISTS WGTOTW;

USE WGTOTW;
-- USE carb14;

-- SET NAMES 'utf8';

DROP TABLE IF EXISTS wgtotw_vote2question;
DROP TABLE IF EXISTS wgtotw_vote2comment;
DROP TABLE IF EXISTS wgtotw_vote2answer;
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
    isAdmin bit,
	created datetime,
	updated datetime,
	deleted datetime,
	active datetime
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_question
(
	id integer primary key not null auto_increment,
    title varchar(80),
    content text,
    created datetime,
    updated datetime,
    deleted datetime,
    upvotes integer,
    downvotes integer,
    questionUserId integer not null,
    foreign key (questionUserId) references wgtotw_user(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_answer
(
	id integer primary key not null auto_increment,
    content text,
    created datetime,
    updated datetime,
    deleted datetime,
    upvotes integer,
    downvotes integer,
    accepted datetime,
    answerUserId integer not null,
    questionId integer not null,
    foreign key (answerUserId) references wgtotw_user(id),
    foreign key (questionId) references wgtotw_question(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_tag
(
	id integer primary key not null auto_increment,
	name varchar(80) unique,
	description varchar(255),
	created datetime,
	updated datetime,
	deleted datetime
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_tag2question
(
	idQuestion integer not null,
	idTag integer not null,
    foreign key (idQuestion) references wgtotw_question(id),
	foreign key (idTag) references wgtotw_tag(id),
	primary key (idQuestion, idTag)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_comment
(
	id integer primary key not null auto_increment,
    content text,
    created datetime,
    updated datetime,
    deleted datetime,
    upvotes integer,
    downvotes integer,
    commentUserId integer not null,
    foreign key (commentUserId) references wgtotw_user(id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_comment2question
(
	idQuestion integer not null,
    idComment integer not null,
    foreign key (idQuestion) references wgtotw_question(id),
    foreign key (idComment) references wgtotw_comment(id),
    primary key (idQuestion, idComment)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_comment2answer
(
	idAnswer integer not null,
    idComment integer not null,
    foreign key (idAnswer) references wgtotw_answer(id),
    foreign key (idComment) references wgtotw_comment(id),
    primary key (idAnswer, idComment)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_vote2answer
(
	idAnswer integer not null,
    idUser integer not null,
    foreign key (idAnswer) references wgtotw_answer(id),
    foreign key (idUser) references wgtotw_user(id),
    primary key (idAnswer, idUser)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_vote2comment
(
	idComment integer not null,
    idUser integer not null,
    foreign key (idComment) references wgtotw_comment(id),
    foreign key (idUser) references wgtotw_user(id),
    primary key (idComment, idUser)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

CREATE TABLE wgtotw_vote2question
(
	idQuestion integer not null,
    idUser integer not null,
    foreign key (idQuestion) references wgtotw_question(id),
    foreign key (idUser) references wgtotw_user(id),
    primary key (idQuestion, idUser)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;