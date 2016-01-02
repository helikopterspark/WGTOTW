DELETE FROM wgtotw_tag2question;
DELETE FROM wgtotw_question;
DELETE FROM wgtotw_tag;
ALTER TABLE wgtotw_tag AUTO_INCREMENT = 1;
INSERT INTO wgtotw_tag (name, description, created) VALUES
('första taggen', 'En första tag', NOW()),
('andra taggen', 'En andra tag', NOW()),
('tredje taggen', 'En tredje tag', NOW()),
('fjärde taggen', 'En fjärde tag', NOW());


ALTER TABLE wgtotw_question AUTO_INCREMENT = 1;
INSERT INTO wgtotw_question (title, content, created, upvotes, downvotes, questionUserId) VALUES 
('Första frågan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NOW() - INTERVAL 3 DAY, 5, 3, 2);

INSERT INTO wgtotw_question (title, content, created, upvotes, downvotes, questionUserId) VALUES 
('Andra frågan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NOW(), 5, 8, 3);

INSERT INTO wgtotw_tag2question (idQuestion, idTag) VALUES
(1,1),
(1,2),
(1,4),
(2,3),
(2,4);


DELETE FROM wgtotw_answer;
INSERT INTO wgtotw_answer (title, content, created, upvotes, downvotes, answerUserId, questionId) VALUES 
('Svar på första frågan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NOW() - INTERVAL 1 DAY, 3, 1, 3, 1);

DELETE FROM wgtotw_comment2question;
INSERT INTO wgtotw_comment (content, created, upvotes, downvotes, userId) VALUES
('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NOW(), 1, 0, 3);
INSERT INTO wgtotw_comment2question (idQuestion, idComment) VALUES (1, 1);

DELETE FROM wgtotw_comment2answer;
INSERT INTO wgtotw_comment (content, created, upvotes, downvotes, userId) VALUES
('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NOW() - INTERVAL 2 DAY, 1, 0, 2);
INSERT INTO wgtotw_comment2answer (idAnswer, idComment) VALUES (1, 2);

INSERT INTO wgtotw_comment (content, created, upvotes, downvotes, userId) VALUES
('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NOW() - INTERVAL 1 DAY, 1, 2, 4);
INSERT INTO wgtotw_comment2question (idQuestion, idComment) VALUES (1, 3);

INSERT INTO wgtotw_answer (title, content, created, upvotes, downvotes, answerUserId, questionId) VALUES 
('Andra svaret på första frågan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NOW() - INTERVAL 2 DAY, 1, 3, 4, 1);

UPDATE wgtotw_answer SET accepted = NOW() WHERE id = 1;

UPDATE wgtotw_answer SET upvotes = 12 WHERE id = 1;

UPDATE wgtotw_user SET isAdmin = 1 WHERE acronym = 'admin';