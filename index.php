<?php
session_start();

$naslov = "Početna";
$fajl = "index";

include("includes.php");

$br = @file_get_contents('preuzimanja.txt');

$br = number_format($br, 0);

$slajd = mysql_query("SELECT * FROM `slajdovi`");

include("./assets/header.php");
?>
<?php     	$replies = query_numrows("SELECT `id` FROM `tiketi` WHERE `user_id` = '{$_SESSION[klijentid]}' AND `status` = '2'");
            if($replies > 0)
            {
                if($replies > 1) $textet = "odgovorena tiketa";
                else $textet = "odgovoren tiket";
?>
            <div id="brzakcija" style="display: block;">
                <p id="h4">Informacija</p>
                <p id="h2">Imate <z><?php echo $replies."</z> ".$textet; ?>. <a href="gp-podrska.php">Klik</a>.
                </p>
            </div> <!-- #brzaakcija end -->
<?php } ?>
			<div id="slajder">
				<div id="slideshow" style="float: left;">
					<div id="slidesContainer">
						<div class="cycle-slideshow"
							data-cycle-auto-height="4:3"
							data-cycle-timeout="6000"	
							data-cycle-prev="#prev"
							data-cycle-next="#next"
							data-cycle-pause-on-hover="true"
							data-cycle-speed="300"	
							data-cycle-slides="> div"
							>	
						<span class="controls">
							<div class="leftControl" id="prev" href="#"></div>
							<div class="rightControl" id="next" href="#"></div>	
						</span>
<?php
							$i = 0;
							while($row = mysql_fetch_array($slajd)) { $i = $i+1;
?>
							<div class="slide">
								<p id="h4"><z>#<?php echo $i; ?></z> <?php echo $row['naslov']; ?><span id="h1" class="right"><?php echo $row['datum']; ?></span></p>
								<div id="content" style="width: 400px;height: 148px;background: url('./assets/img/slider/2.png') no-repeat">
									<img width="398" height="148" src="<?php echo $row['slika']; ?>" />
									<div id="msg">
										<p><?php echo $row['text']; ?></p>
									</div>
								</div>
							</div>
<?php
							}
?>
						</div>
					</div> 
				</div>
				<div id="skinics">
					<a href="#" onclick="skidanjecs()">
						<i class="icon-cloud-download"></i>
					</a>
					<p id="dwn"><?php echo $jezik['text31']; ?><br /><span style="font-size: 12px;" rel="tipr" title="Skidanja sa sajta"><?php echo $jezik['text32']; ?>: <z><?php echo $br; ?></z></span> <span style="font-size: 9px; font-style: italic;"><a href="#">Više...</a></p>
				</div>
				<div id="etnovosti">
					<h3>Novosti na sajtu</h3>
					<ul>
						<li><span style="color: white; font-size: 10px;"></span> - <a>Update v1.2</a></li>
						<li><span style="color: white; font-size: 10px;"></span> - <a>Nova verzija sajta v1.2</a></li>
						<li><span style="color: white; font-size: 10px;"></span> - Podrska ili pomoc 24/7 na FB-u <a target="_BLANK" href="https://www.facebook.com/profile.php?id=100033976833138"><span id="blinknew" style="color: #96e400;"><font color="white">Vasquez</a></li>
						<li><span style="color: white; font-size: 10px;"></span> - Dodata prodaja music bota</li>
						<li><span style="color: white; font-size: 10px;"></span> - Dodata prodaja sinus bota</li>
						<li><span style="color: white; font-size: 10px;"></span> - Panel odradjen od strane <a target="_BLANK" href="https://www.facebook.com/profile.php?id=100033976833138"><span id="blinknew" style="color: #96e400;"><font color="white">Vasquez</a></li>

					</ul>
				</div>
			</div><!-- #slajder end -->
			
			<div id="sep"></div> <!-- #sep end -->

			<div id="serverscroll">
				<ul id="skrolsrv">
<?php
					require("./includes/libs/lgsl/lgsl_class.php");

					$query = mysql_query("SELECT s.igra, bi.ip, s.port, s.id, s.rank 
						FROM serveri s, box b, boxip bi 
						WHERE s.status = 'Aktivan' 
						AND s.startovan = '1' 
						AND s.free = 'Ne' 						
						AND b.boxid = s.box_id 
						AND bi.ipid = s.ip_id 
                                                ORDER BY RAND()
						LIMIT 5");

					while($row = mysql_fetch_assoc($query)) 
					{
						if($row['igra'] == "1") { $mapc = "cs"; $querytype = "halflife"; }
						else if($row['igra'] == "2") { $mapc = "samp"; $querytype = "samp"; }
						else if($row['igra'] == "3") { $mapc = "minecraft"; $querytype = "minecraft"; }

						$serverlgsl = lgsl_query_cached($querytype, $row['ip'], $row['port'], $row['port'], $row['port'], "sep", $row['id']);

						if(@$serverlgsl['b']['status'] == '1')
						{
							$srv = array(
								'name' => @$serverlgsl['s']['name'],
								'map' => @$serverlgsl['s']['map'],
								'players' => @$serverlgsl['s']['players'].'/'.@$serverlgsl['s']['playersmax'],
								'ipaddress' => $row['ip'].":".$row['port'],
								'rank' => $row['rank'],
							);
						}
						else
						{
							$srv = array(
								'name' => $row['name'],
								'map' => 'offline',
								'players' => '<font color="red">Offline</font>',
								'ipaddress' => $row['ip'].":".$row['port'],
								'rank' => $row['rank'],
							);
						}

?>
					<li>
						<div id="mapimg">
							<?php if ($row['igra'] == "1")  {?> <img width="90" height="70" src="http://banners.gametracker.rs/map/cs/<?php echo $srv['map']; ?>.jpg" /> <?php } ?>
							<?php if ($row['igra'] == "2")  {?> <img width="90" height="70" src="http://banners.gametracker.rs/map/samp/<?php echo $srv['map']; ?>.jpg" /> <?php } ?>
							<?php if ($row['igra'] == "3")  {?> <img width="90" height="70" src="http://banners.gametracker.rs/map/minecraft/<?php echo $srv['map']; ?>.jpg" /> <?php } ?>
						</div>
						<div id="infos">
							<a href="server.php?id=<?php echo $row['id']; ?>"><?php echo $srv['name']; ?></a><br />
							<?php echo $srv['ipaddress']; ?><br />						
							Players: <z><?php echo $srv['players']; ?></z><br />
							Rank: <z>#<?php echo $srv['rank']; ?></z>
						</div>
					</li>
<?php
					}
?>
				</ul>
			</div>

			<div id="sep"></div> <!-- #sep end -->
<!---
			<a href="#" onclick="skidanjecs()"><div id="promocija">
<div id="img">
			</div>
			</div></a> -->
 <!-- #promocija end -->
<!-- vps start ciker 1337 -->

<style media="screen" type="text/css">



.text1 {
    text-align: center;
}

.text2 {
    font-weight: bold;
    text-align: center;
}

.textgreen {
    font-weight: bold;
    text-align: center;
	color: #00FF00;
}

.kurac {
    font-weight: bold;
    text-align: center;
	color: #FF00FF;
}
</style>

<div class="narucii">
				
					<div id="gamePonude">
				<div class="gamePonude">
					
					<div id="GameSer"></div>

					<div class="gameServer">
											<li>
							<div class="game cs1">
								
								<img src="/img/game/cs1.6.png" alt="Counter Strike 1.6">
								<h3>Counter-Strike 1.6</h3>

								<div class="kupi">
									<a href="/naruci.php"><img src="/img/icon/buy.png"> Naruci</a>
								</div>

							</div>
						</li>

						<li>
							<div class="game csGo">
								<img src="/img/game/cs-go.png" alt="Counter Strike GO">
								<h3>Music Bot</h3>

								<div class="kupi">
									<a href="/naruci.php"><img src="/img/icon/buy.png"> Naruci</a>
								</div>
							</div>
						</li>

						<li>
							<div class="game csGo">
								<img src="/img/game/samp.png" alt="Counter Strike 1.6">
								<h3>GTA SAMP</h3>

								<div class="kupi">
									<a href="/naruci.php"><img src="/img/icon/buy.png"> Naruci</a>
								</div>

							</div>
						</li>

						<li>
							<div class="game csGo">
								<img src="/img/game/ts3.png" alt="Counter Strike 1.6">
								<h3>TeamSpeak 3</h3>

								<div class="kupi">
									<a href="/naruci.php"><img src="/img/icon/buy.png"> Naruci</a>
								</div>
							</div>
						</li>

						<li>
							<div class="game csGo">
								<img src="/img/game/mc.png" alt="Counter Strike 1.6">
								<h3>Minecraft</h3>

								<div class="kupi">
									<a href="/naruci.php"><img src="/img/icon/buy.png"> Naruci</a>
								</div>
							</div>
						</li>

						<li>
							<div class="game csGo">
								<img src="/img/game/vps.png" alt="Counter Strike 1.6">
								<h3>Sinus Bot</h3>

								<div class="kupi">
									<a href="/naruci.php"><img src="/img/icon/buy.png"> Naruci</a>
								</div>
							</div>
						</li>

					</div>
					<div id="infoGame">
						<div class="infoGame"></div>
					</div>

				</div>
			</div>
<?php
include("./assets/footer.php");
?>
