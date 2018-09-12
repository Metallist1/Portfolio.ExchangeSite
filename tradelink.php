<?php
	$id = $_POST["who"];
	$tradelink = $_POST["link"];
	$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
	if (strpos($tradelink, 'https://steamcommunity.com/trade') !== false && strlen($tradelink) <=80){
		$query = $db->prepare("UPDATE `users` SET `tradeurl` = :link WHERE `steamid` = :id");
		$query->execute(array(
   			 "link" => $tradelink,
  			 "id" => $id
		));
	echo json_encode(["Success"]);
	}else echo json_encode(['error' => "Bad trade link"]);
?>