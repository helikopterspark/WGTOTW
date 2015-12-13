INSERT INTO wgtotw_tag (name, description, created) VALUES
('första taggen', 'En första tag', NOW()),
('andra taggen', 'En andra tag', NOW()),
('tredje taggen', 'En tredje tag', NOW()),
('fjärde taggen', 'En fjärde tag', NOW());

DELETE FROM wgtotw_question;
ALTER TABLE wgtotw_question AUTO_INCREMENT = 1;
INSERT INTO wgtotw_question (title, data, created, upvotes, downvotes, questionUserId) VALUES 
('Första frågan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NOW(), 5, 3, 2);

INSERT INTO wgtotw_question (title, data, created, upvotes, downvotes, questionUserId) VALUES 
('Andra frågan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NOW(), 5, 8, 3);

DELETE FROM wgtotw_tag2question;
INSERT INTO wgtotw_tag2question (idQuestion, idTag) VALUES
(1,1),
(1,2),
(1,4),
(2,3),
(2,4);

SELECT * FROM wgtotw_question;

SELECT idTag FROM wgtotw_tag2question WHERE idQuestion = 1;