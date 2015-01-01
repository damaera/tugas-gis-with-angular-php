<?php

function connectDB() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="simpedal";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function fetchAll($sql) {
	try {
		$db = connectDB();
		$stmt = $db->query($sql);  
		$items = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($items);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function fetchRet($sql) {
	try {
		$db = connectDB();
		$stmt = $db->query($sql);  
		$items = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		return $items;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function fetchObject($sql) {
	
	try {
		$db = connectDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$item = $stmt->fetchObject();  
		$db = null;
		echo json_encode($item); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function executeSql($sql) {
	try {
		$db = connectDB();
		$stmt = $db->prepare($sql); 
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
?>