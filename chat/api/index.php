<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/messages', 'getMessages');
$app->get('/messages/:id', 'getMessagesAfter');
$app->post('/messages', 'addMessage');
$app->delete('/messages/:id', 'deleteMessage');

$app->run();

function getMessages() {
    $sql = "SELECT * FROM (SELECT * FROM message ORDER BY msg_time DESC LIMIT 10) AS `table` ORDER by msg_time ASC";
    try {
        $db = getChatDbConnection();
        $stmt = $db->query($sql);
        $messages = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($messages);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMessagesAfter($id) {
    $sql = "SELECT * FROM (SELECT * FROM message ORDER BY msg_time DESC LIMIT 10) AS `table` WHERE id > $id ORDER by msg_time ASC";
    try {
        $db = getChatDbConnection();
        $stmt = $db->query($sql);
        $messages = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($messages);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addMessage() {
    error_log('addMessage\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $message = json_decode($request->getBody());

    $sql = "INSERT INTO message (msg_time, username, contents) VALUES (NOW(), :username, :contents)";
    try {
        $db = getChatDbConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $message->username);
        $stmt->bindParam("contents", $message->contents);
        $stmt->execute();
        $message->id = $db->lastInsertId();
        $db = null;
        echo json_encode($message);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteMessage($id) {
	$sql = "DELETE FROM message WHERE id=:id";
	try {
		$db = getChatDbConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getChatDbConnection() {
    $dbhost="127.0.0.1";
    $dbuser="app";
    $dbpass="1234pass";
    $dbname="chat";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
