<?php
	$id = $_POST["who"];
	$botid = $_POST["bot"];
	$Ugame = $_POST["usergame"];
	$Bgame = $_POST["botgame"];
	$Uitems = $_POST["Uitems"];
	$Bitems = $_POST["Bitems"];
	$Bassets = $_POST["Bassets"];
	$Uassets = $_POST["Uassets"];
	$Bprice = $_POST["Bprice"];
	$Uprice = $_POST["Uprice"];
	$bonus = $_POST["bonus"];
	$Userprice = 0 ;
	$Botprice = 0 ;
	$useritems="";
	$userasset="";
	$botoitems="";
	$botoasset="";
	if($Ugame == $Bgame && bonus == 1) $mod = 0.97; //negerai , pakeisti kad imtu arba pati mod arba bonus .
	else if($Ugame == $Bgame) $mod = 0.95;
	else if($Ugame != $Bgame && bonus == 1) $mod = 0.92;
	else $mod = 0.90;
	$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
	$stmt = $db->prepare("SELECT `Price` FROM `itemsinfo` WHERE `market_hash_name` = ?");
		if(count($Uitems) == count($Uassets) && count($Bitems) == count($Bassets)){
			for ($x = 0; $x <count($Uitems) ; $x++) {
				$stmt->execute([$Uitems[$x]]);
				$result = $stmt -> fetch();
				$Userprice = $Userprice+($result["Price"]*$mod);
				$useritems = $Uitems[$x] . ' / ' . $useritems;
				$userasset = $Uassets[$x] . ' / ' . $userasset;
			} 
			for ($x = 0; $x <count($Bitems) ; $x++) {
				$stmt->execute([$Bitems[$x]]);
				$result = $stmt -> fetch();
				$Botprice = $Botprice+$result["Price"];
				$botoitems = $Bitems[$x] . ' / ' . $botoitems;
				$botoasset = $Bassets[$x] . ' / ' . $botoasset;
			}
			if($Userprice>=$Botprice && $Userprice >0 && $Botprice >=0){
				$statement = $db->prepare("INSERT INTO `queue`(`userid`, `botoid`, `usergame`,`botgame`,`useritems`,`userioasset`,`userioprice`,`botitems`,`botoassetid`,`botoprice`,`status`,`mod`)
	    			VALUES(:userid, :botoid, :usergame, :botgame, :useritems, :userioasset, :userioprice, :botitems, :botoassetid, :botoprice, :status, :mod)");
				$statement->execute(array(
					"userid" => $id, 
					"botoid" => $botid, 
					"usergame" => $Ugame,
					"botgame" => $Bgame,
					"useritems" => $useritems,
					"userioasset" => $userasset,
					"userioprice" => $Uprice,
					"botitems" => $botoitems,
					"botoassetid" => $botoasset,
					"botoprice" => $Bprice,
					"status" => "waiting",
					"mod" => $mod
				));
				$idoftrade= $db->lastInsertId();
				$tradeid = rekursija($db , 6 , $idoftrade);
				echo json_encode($tradeid);
			}else echo json_encode(['error' => "You don't have enough value"]);
		}else echo json_encode(['error' => "You are missing some items."]);
		function rekursija($db ,$n,$idNumb) {
			sleep(2);
			$stmt = $db->prepare("SELECT `offer_id` FROM `queue` WHERE `id` = ?");
			$stmt->execute([$idNumb]);
			$result = $stmt -> fetch();
			if ($n === 0) {
				$stmt = $db->prepare("SELECT `status` FROM `queue` WHERE `id` = ?");
				$stmt->execute([$idNumb]);
				$result = $stmt -> fetch();
					switch ($result["status"]) {
	 				  	case "No tradelink":
							return ['error' => "Your trade link is not set."];  
  							break;
	    					case "User is banned":
							return ['error' => "Your offer was not sent."]; 
	      						break;
	   					case "Escrow error":
							return ['error' => "Escrow error. Please check if you have mobile authenticator on and if your trade link is set correctly."];
							break;
	  					case "Exchange boto error":
							return ['error' => "Error. The items you want to get no longer exist on the bot"];
	        					break;
	    					case "Cant load user inv":
							return ['error' => "Error. Your inventory is private or steam is down."];
	       						break;
	   					case "Cant load bot inv":
							return ['error' => "Error. The bot is having problems , please contact the admins of this page !"];
        						break;
	    					case "Exchange userio error":
							return ['error' => "Error. You don't have enough items."];
	       	 					break;
	    					case "User doesnt have escrow":
							return ['error' => "Escrow Error. You don't have mobile authenticator."];
	   						break;
    						case "Sending error":
							return ['error' => "Error. Offer can't be sent , please check if you have any active trades from this bot."];
	        					break;
	    					default:
							return ['error' => "Unknown Error. It's possible that the offer was not sent , please try again later !"];  
					}
			}else if($result["offer_id"] == 0) return rekursija($db ,$n-1,$idNumb);
			else return $result["offer_id"];
	  	}
?>