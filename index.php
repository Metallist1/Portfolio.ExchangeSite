<!DOCTYPE html>
<html>
<body>
		<style>
		body {
			background-image: url(<?php $a = array('/Newproject/images/bg1.jpg','/Newproject/images/bg2.jpg','/Newproject/images/bg3.jpg', '/Newproject/images/bg4.jpg', '/Newproject/images/bg5.jpg', '/Newproject/images/bg6.jpg', '/Newproject/images/bg7.jpg', '/Newproject/images/bg8.jpg', '/Newproject/images/bg9.jpg'); echo $a[array_rand($a)];?>);"
			background-repeat: no-repeat;
			background-attachment: fixed;		
		}
		</style>
</body>
	<head>
    		<meta charset="utf-8">
		<title>Sazone.LT Trading website</title>
		<link rel="stylesheet" href="css/projektas.css">
    		<link rel="stylesheet" href="css/metro-bootstrap.css">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	</head>
	<body class="metro"> 
	<!-- javascript -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script  src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js"></script>
		<script src="/Newproject/js/projektas.js"></script>
		<script src="/Newproject/js/bootstrap.js"></script>
	<!-- header -->
		<header>
			<div class= "header">
				<div class="logo"><img src="/Newproject/images/logo.png" alt="Logo" ></div>
				<div class= "midNav">
					<ul id="Nav">
						<li>
							<a href="/" >Hub</a>
						</li>
						<li>

							<a class="modalOpen" >Prices</a>
							<div id="price" class="modal">
  								<div class="modal-content">
									<div class="modal-inner">
    										<span class="close">&times;</span>

              <table class="ui inverted table">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Users Price(same game)</th>
		<th>Users Price(diffrent game)</th>
                    <th>Our Price</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Csgo Keys</td>
                    <td>100%</td>
			<td>95%</td>
                    <td>105%</td>
                  </tr>
                  <tr>
                    <td>Csgo Knifes</td>
                    <td>95%</td>
			<td>90%</td>
                    <td>100%</td>
                  </tr>
                  <tr>
                    <td>Csgo StatTrak&trade; Knifes</td>
                    <td>80%</td>
<td>75%</td>
                    <td>85%</td>
                  </tr>
                  <tr>
                    <td>Csgo Common Weapons</td>
                    <td>100%</td>
<td>95%</td>
                    <td>105%</td>
                  </tr>
                  <tr>
                    <td>Other items</td>
                    <td>90%</td>
<td>85%</td>
                    <td>95%</td>
                  </tr>
                  <tr>
                    <td>Items<10 cents</td>
                    <td>Donations only</td>
<td>Donations only</td>
                    <td>Will be used for giveaways</td>
                  </tr>
                  <tr>
                    <td>Souvenirs</td>
                    <td>20%</td>
<td>15%</td>
                    <td>80%</td>
                  </tr>
                </tbody>
              </table>
									</div>
  								</div>
							</div>

						</li>
						<li>
							<a class="modalOpen" >FAQ</a>
							<div id="FAQ" class="modal">
  								<div class="modal-content">
									<div class="modal-inner">
    										<span class="close">&times;</span>
<p><strong>Q: Is it possible to lower websites commision ?</strong></p>
<p>A: Not yet , but it is WIP.</p>
<p><strong>Q: I dont see some items from my inventory , why is it like that ?</strong></p>
<p> A: Website is configured to filter our untradable items such as operation coins and items that are untradable .</p>
<p> Also it should be noted that any item you brought in steam market is untradable for 7 days and DOTA compendium items are untradable for longer periods </p>
<p> If your items are not untradable , refresh the page , if the problem remains . Wait a bit and refresh. </p>
<p><strong>Q: How do we get our prices ?</strong></p>
<p>A: We use our own made price api , which takes out most likely candidates for instability and price abuse .</p>
<p><strong>Q: Unstable price , what is that ?</strong></p>
<p>A: Due to how our price api acts , your item was deemed abusable , if your item is rare then this pricing might never be lifted , but if it is not rare , check in a day or two , it might change if prices become normal.</p>
<p><strong>Q: Is it risky using your site ?</strong></p>
<p>A: Our site values your safety. Due to this bots send YOU the offer , we trust you can check the offers contents and if they are wrong to notify the admins asap.</p>
<p><strong>Q: I dislike the design of your site but i still want to trade with your bots , can i do it ?</strong></p>
<p>A: Sure, our bots are capable of trading on their own , but take note that they do not apply any discount bonuses.</p>
<p><strong>Q: How can i keep op on news about your site ?</strong></p>
<p>A: By joining our steam group , following our twitter or liking our fb page . We will notify if there are any bonuses or major changes to the site.</p>
  									</div>
								</div>
							</div>
						</li>
						<li>
							<a >How to use this website</a>
						</li>

					</ul>
				</div>
				<div class= "rightNav">
					<div class= "usersonline">
						<?php
							session_start();
							$session=session_id();
							$time=time();
							$time_check=$time-300;
							$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
							$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
							$stmt = $db->prepare("SELECT COUNT(*) FROM online_users WHERE session = ?");
							$stmt->execute([$session]);
							$number_of_rows = $stmt->fetchColumn(); 
								if($number_of_rows=="0"){ 
									$statement = $db->prepare("INSERT INTO online_users(session, time) VALUES(:sesionsss, :timess)");
									$statement->execute(array("sesionsss" => $session,"timess" => $time));
								}else {
									$sql2 = $db->prepare("UPDATE online_users SET time = :timedoc WHERE session = :sesonke");
									$sql2->execute(array("sesonke" => $session,"timedoc" => $time));
								}
							$res = $db->query('SELECT COUNT(*) FROM online_users');
							$count_user_online = $res->fetchColumn();
							echo ' Users online: '. $count_user_online. '<Br>';
							$stm = $db->prepare("DELETE FROM online_users WHERE time < :timestamp");
							$stm->bindParam(':timestamp', $time_check);
							$stm->execute();
						?>
					</div>
					<a  class="languages">
						<img style="margin-top:3px;" src="images/GB.png">
					</a>
					<div class= "Login">
						<?php if(!isset($_COOKIE["user"])): ?>
							<a href="/Newproject/login.php"><img style="margin-top:3px;" src="images/steam.png"></a>
						<?php else: ?>
							<div class="dropdown" cost= "eff">
								<a>
									<img class="dropdownOpen rounded" src="<?=$_COOKIE["user"]?>">
								</a>
 								<div id="meniu" class="dropdown-content right">
									<ul id="userdrop">
										<li>
											<a class="modalOpen">Trade link</a>
											<div id="myModal" class="modal">
  												<div class="modal-content">
													<div class="modal-inner">
    														<span class="close">&times;</span>
<a class= "links" href="http://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank" >Click here to get your trade url</a>
														<form>
															<input type="text" class="Trade_link" size="74" id="Tradelink" placeholder="https://steamcommunity.com/trade...">
														</form>
														<div class="TlinkError" >Invalid Trade Link <Br></div>
														<div class="TlinkSucc" >Your Trade Link Has Been Set Succesfully !<Br></div>
														<div class="submit" >Submit Your Trade Link</div>
  													</div>
												</div>
											</div>
										</li>
										<li>
											<a class="modalOpen">Trade history</a>
											<div id="historylist" class="modal">
												<div class="modal-content">
													<div class="modal-inner">
    														<span class="close">&times;</span>
														<table class="table">
															<thead>
																<tr>
																	<th>Your trade ID</th>
																	<th>Bot Steam ID</th>
																	<th>Your game</th>
																	<th>Bots game</th>
																	<th>Your item count</th>
																	<th>Websites item count</th>
																	<th>Your value</th>
																	<th>Websites value</th>
																	<th>State of your offer</th>
																	<th>Your price modifier</th>
																	<th>Offer ID</th>
																</tr>
															</thead>
															<tbody>
															<?php
																$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
																$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
																$stmt = $db->prepare("SELECT * FROM `queue` WHERE `userid` = ? ORDER BY `id` DESC LIMIT 10");
																$stmt->execute([$_COOKIE["who"]]);
    																	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        																	echo '<tr><td>'.$row["id"].'</td>';
																		echo '<td>'.$row["botoid"].'</td>';
																		echo '<td>'.$row["usergame"].'</td>';
																		echo '<td>'.$row["botgame"].'</td>';
																		$pieces = explode(" / ", $row['useritems']);
																		$length = count($pieces)-1;
																		$piecess = explode(" / ", $row['botitems']);
																		$lengt = count($piecess)-1;
																		echo '<td>'.$length.'</td>';
																		echo '<td>'.$lengt.'</td>';
																		echo '<td>'.$row["userioprice"].'</td>';
																		echo '<td>'.$row["botoprice"].'</td>';
																		switch ($row['status']) {
    																			case "Accepted":
        																			echo'<td><b style="color:green;">You accepted this offer</b></td>';
      																				break;
    																			case "Invalid":
       																				echo '<td><b style="color:red;">Offer is Invalid</b></td>';
        																			break;
    																			case "Active":
        																			echo'<td><b style="color:green;">Offer is currectly active</b></td>';
        																			break;
    																			case "Countered":
        																			echo'<td><b style="color:red;">Offer countered by you</b></td>';
        																			break;
    																			case "Declined":
        																			echo'<td><b style="color:red;">You declined this offer</b></td>';
        																			break;
    																			case "InvalidItems":
        																			echo'<td><b style="color:red;">Offer is Invalid</b></td>';
        																			break;
    																			case "User is banned":
        																			echo'<td><b style="color:red;">We know what you did.</b></td>';
        																			break;
    																			case "First checker error":
        																			echo'<td><b style="color:red;">Mission failed, we will get em next time </b></td>';
        																			break;
    																			case "Escrow error":
        																			echo'<td><b style="color:red;">Escrow error , check your trade link.</b></td>';
        																			break;
    																			case "Exchange userio error":
        																			echo'<td><b style="color:red;">You dont have some items</b></td>';
        																			break;
    																			case "Second checker error":
        																			echo'<td><b style="color:red;">Do not edit the prices.This is your warning</b></td>';
        																			break;
    																			case "Sending error":
        																			echo'<td><b style="color:red;">Offer will come shortly.</b></td>';
        																			break;
    																			case "Exchange bot error":
        																			echo'<td><b style="color:red;">Bot lacks some items</b></td>';
        																			break;
    																			default:
        																			echo'<td><b style="color:red;">Error</b></td>';
																		}
																		echo '<td>'.$row["mod"].'</td>';
																		echo '<td>'.$row["offer_id"].'</td></tr>';
																	}
															?>
															</tbody>
														</table>
 				 									</div>
												</div>
											</div>
										</li>
				 						<li>
											<a href="/Newproject/logout.php">Log out</a>
										</li>
									</ul>
 								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</header>
	<!-- Middle -->
	<div class="main">
		<center>
			<div class="small" id="trade" onclick= "trade()">Trade
				<div class="modal">
  					<div class="modal-content">
						<div class="modal-inner">
    							Your trade is being processed , please wait.
  						</div>
					</div>
				</div>
				<div class="modal">
  					<div class="modal-content" >
						<div class="modal-inner" id="modalSucc"></div>
					</div>
				</div>
				<div class="modal">
  					<div class="modal-content">
						<div class="modal-inner" id="modalError"></div>
					</div>
				</div>
			</div>
			<div class="tradesmade" >
				<?php
					$db = new PDO('mysql:host=localhost;dbname=csgoall', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
					$res = $db->query('SELECT COUNT(*) FROM queue WHERE status = "accepted"');
					$count_trades = $res->fetchColumn();
					echo ' Trades made: '. $count_trades. '';
				?>
			</div>
		</center>
		<div class="contain" id="containuser">
			<div class="box">
				<div class="top">
					<ul>
						<li>
							<div id ="useridentification">You offer</div>
</li>
<li>
							<div id ="userprice">0.00</div>
						</li>
					</ul>
				</div>
				<div class="user_inventory_selected" id = "Uselected"></div>
			</div>
			<div class="box">
				<div class="top">
					<ul>
<li style="width: 100px;">
<div class="refreshPlace"><img class="refreshU" src="images/refresh.png">
</div>
</li>
						<li>

							<div class="selectbarLeft">
								<a class="dropdownOpen">Change Your Game</a>
 					 			<div id="gamesU" class="dropdown-content left">
   									<a onclick="loadInv(730)" >Csgo</a>
									<a onclick="loadInv(570)" >Dota 2</a>
 				 				</div>
							</div>

						</li>
<li>
<input type="text" id="ItemInput" onkeyup="search('user')" placeholder="Search...">
</li>
						<li style="float:right">
							<div class="selectbarRight">
								<a class="dropdownOpen">Sort User</a>
 					 			<div id="SortU" class="dropdown-content right">
									<a onclick="sort(1,1)" >Highest price first</a>
   									<a onclick="sort(0,1)" >Lowest price first</a>
 				 				</div>
							</div>

						</li>
					</ul>
				</div>
				<div class="user_inventory" id = "user"></div>
			</div>
		</div>
		<div class="contain" id="containbot">
			<div class="box">
				<div class="top">
					<ul >
						<li>		
							<div id ="botprice">0.00</div>
						</li>
<li>	
							<div id ="botidentification">You receive</div>
</li>

					</ul>
				</div>
				<div class="bot_inventory_selected" id ="Bselected"></div>
			</div>
			<div class="box">
				<div class="top">
					<ul >
<li style="width: 18.5%;">
<div class="refreshPlace"><img class="refreshB" src="images/refresh.png"></div>
</li>
						<li style="width: 75%;">	
							<div class="selectbarLeft">
								<a class="dropdownOpen">Change Bot</a>
 								<div id="bots" class="dropdown-content left">
   									<a onclick="loadbot(1)">Bot1</a>
									<a onclick="loadbot(2)">Bot2</a>
									<a onclick="loadbot(3)">Bot3</a>
 				 				</div>
							</div>
						</li>
						<li>
							<div class="selectbarMid">			
								<a class="dropdownOpen ">Change Bots Game</a>
 								<div id="gamesB" class="dropdown-content mid">
   									<a onclick="loadbotinv(730)"  >Csgo</a>
									<a onclick="loadbotinv(570)" >Dota 2</a>
 				 				</div>
							</div>


						</li>
<li>
<input type="text" id="ItemsInput" onkeyup="search('bot')" placeholder="Search...">
						</li>
						<li style="width: 50%;">		
							<div class="selectbarRight">		
								<a class="dropdownOpen">Sort Bot</a>
 					 			<div id="SortB" class="dropdown-content right">
									<a onclick="sort(1,0)" >Highest price first</a>
   									<a onclick="sort(0,0)" >Lowest price first</a>
 				 				</div>

							</div>
</li>

					</ul>
				</div>
				<div class="bot_inventory"id= "bot"></div>
			</div>
		</div>
	</div>
	<footer>
		<p>Website made by:Alpha Awper </p>
	</footer>
</body>
</html>