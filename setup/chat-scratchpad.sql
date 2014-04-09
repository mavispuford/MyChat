USE chat;

INSERT INTO chat.message (msg_time, username, contents) VALUES (NOW(), 'MrPerson', 'Testing, one, two, three!');

SELECT * FROM chat.message;
SELECT * FROM message ORDER BY msg_time DESC LIMIT 10;