<?php

require 'Slim/Slim.php';

$app = new Slim();

//$app->get('/wines', 'getWines');
//$app->get('/wines/:id',	'getWine');
//$app->get('/wines/search/:query', 'findByName');
//$app->post('/wines', 'addWine');
//$app->put('/wines/:id', 'updateWine');
//$app->delete('/wines/:id', 'deleteWine');
$app->get('/messages', 'getMessages');
$app->get('/messages/:id', 'getMessagesAfter');
$app->post('/messages', 'addMessage');
$app->delete('/messages/:id', 'deleteMessage');

$app->run();

//function getWines() {
//	$sql = "select * FROM wine ORDER BY name";
//	try {
//		$db = getConnection();
//		$stmt = $db->query($sql);
//		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
//		$db = null;
//		echo json_encode($wines);
//	} catch(PDOException $e) {
//		echo '{"error":{"text":'. $e->getMessage() .'}}';
//	}
//}
//
//function getWine($id) {
//	$sql = "SELECT * FROM wine WHERE id=:id";
//	try {
//		$db = getConnection();
//		$stmt = $db->prepare($sql);
//		$stmt->bindParam("id", $id);
//		$stmt->execute();
//		$wine = $stmt->fetchObject();
//		$db = null;
//		echo json_encode($wine);
//	} catch(PDOException $e) {
//		echo '{"error":{"text":'. $e->getMessage() .'}}';
//	}
//}
//
//function addWine() {
//	error_log('addWine\n', 3, '/var/tmp/php.log');
//	$request = Slim::getInstance()->request();
//	$wine = json_decode($request->getBody());
//	$sql = "INSERT INTO wine (name, grapes, country, region, year, description) VALUES (:name, :grapes, :country, :region, :year, :description)";
//	try {
//		$db = getConnection();
//		$stmt = $db->prepare($sql);
//		$stmt->bindParam("name", $wine->name);
//		$stmt->bindParam("grapes", $wine->grapes);
//		$stmt->bindParam("country", $wine->country);
//		$stmt->bindParam("region", $wine->region);
//		$stmt->bindParam("year", $wine->year);
//		$stmt->bindParam("description", $wine->description);
//		$stmt->execute();
//		$wine->id = $db->lastInsertId();
//		$db = null;
//		echo json_encode($wine);
//	} catch(PDOException $e) {
//		error_log($e->getMessage(), 3, '/var/tmp/php.log');
//		echo '{"error":{"text":'. $e->getMessage() .'}}';
//	}
//}
//
//function updateWine($id) {
//	$request = Slim::getInstance()->request();
//	$body = $request->getBody();
//	$wine = json_decode($body);
//	$sql = "UPDATE wine SET name=:name, grapes=:grapes, country=:country, region=:region, year=:year, description=:description WHERE id=:id";
//	try {
//		$db = getConnection();
//		$stmt = $db->prepare($sql);
//		$stmt->bindParam("name", $wine->name);
//		$stmt->bindParam("grapes", $wine->grapes);
//		$stmt->bindParam("country", $wine->country);
//		$stmt->bindParam("region", $wine->region);
//		$stmt->bindParam("year", $wine->year);
//		$stmt->bindParam("description", $wine->description);
//		$stmt->bindParam("id", $id);
//		$stmt->execute();
//		$db = null;
//		echo json_encode($wine);
//	} catch(PDOException $e) {
//		echo '{"error":{"text":'. $e->getMessage() .'}}';
//	}
//}
//
//function deleteWine($id) {
//	$sql = "DELETE FROM wine WHERE id=:id";
//	try {
//		$db = getConnection();
//		$stmt = $db->prepare($sql);
//		$stmt->bindParam("id", $id);
//		$stmt->execute();
//		$db = null;
//	} catch(PDOException $e) {
//		echo '{"error":{"text":'. $e->getMessage() .'}}';
//	}
//}
//
//function findByName($query) {
//	$sql = "SELECT * FROM wine WHERE UPPER(name) LIKE :query ORDER BY name";
//	try {
//		$db = getConnection();
//		$stmt = $db->prepare($sql);
//		$query = "%".$query."%";
//		$stmt->bindParam("query", $query);
//		$stmt->execute();
//		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
//		$db = null;
//		echo '{"wine": ' . json_encode($wines) . '}';
//	} catch(PDOException $e) {
//		echo '{"error":{"text":'. $e->getMessage() .'}}';
//	}
//}
//
//function getConnection() {
//	$dbhost="127.0.0.1";
//	$dbuser="app";
//	$dbpass="1234pass";
//	$dbname="cellar";
//	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
//	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	return $dbh;
//}

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
