<?php
session_start();

include 'assets/functions/loginfunctions.php';
include 'assets/functions/generalfunctions.php';
include 'assets/functions/mcuserapi.php';

if (!isset($_SESSION['login'])) {
	if ($_SESSION['login'] != true) {
		header("location: login");
	}
}

if (!hasPermission("console")) {
	header("location: index");
}
?>

    <!DOCTYPE html>
    <html>

    <head lang="en">
        <meta charset="UTF-8">
        <title>Willow - RoyalCraft.co</title>

        <!-- cdn css -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <!-- custom css -->
        <link href='assets/css/home.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/console.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/notification.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/animate.css' rel='stylesheet' type='text/css'>

        <!-- icon -->
        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />

    </head>

    <body>
        <!-- Notifications start -->
        <div class="container">
            <div class="row">
                <div class="com-md-12">
                    <div class="notification alert alert-danger error-change" role="alert">
                        <span class="fa fa-minus-circle"></span> Invalid password!
                    </div>

                    <div class="notification alert alert-success changed">
                        <span class="fa fa-check-circle"></span> Successfully changed password!
                    </div>
                </div>
            </div>
        </div>
        <!-- Notifications end -->

        <div id="navbar-wrapper">
            <header>
                <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                            <a class="navbar-brand" href="#">Willow</a>
                        </div>
                        <div id="navbar-collapse" class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a id="user-profile" href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="https://minotar.net/avatar/<?php echo $_SESSION['mcuser'] ?>" class="img-responsive img-thumbnail img-circle"> <?php echo $_SESSION['username'] ?></a>
                                    <ul class="dropdown-menu dropdown-block" role="menu">
                                        <li><a data-toggle="modal" data-target="#changePassword" href="#">Change password</a></li>
                                        <li><a href="logout.php">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
        <div id="wrapper">
            <div id="sidebar-wrapper">
                <aside id="sidebar">
                    <ul id="sidemenu" class="sidebar-nav">
                        <li>
                            <a href="index.php">
								<span class="sidebar-icon"><i class="fa fa-home"></i></span>
								<span class="sidebar-title">Home</span>
							</a>
                        </li>
                        <li>
                            <a href="users.php" <?php if (!(hasPermission("view-users"))) { echo "id=\"disabled\""; }?>>
								<span class="sidebar-icon"><i class="fa fa-users"></i></span>
								<span class="sidebar-title">Users</span>
							</a>
                        </li>
                        <li>
                            <a href="#" <?php if (!(hasPermission("view-wiki"))) { echo "id=\"disabled\""; }?>>
								<span class="sidebar-icon"><i class="fa fa-file-text"></i></span>
								<span class="sidebar-title">Wiki</span>
							</a>
                        </li>
                        <li>
                            <a href="bans.php" <?php if (!(hasPermission("view-bans"))) { echo "id=\"disabled\""; }?>>
								<span class="sidebar-icon"><i class="fa fa-book"></i></span>
								<span class="sidebar-title">Bans</span>
							</a>
                        </li>
                        <li>
                            <a href="stats.php" <?php if (!(hasPermission("view-stats"))) { echo "id=\"disabled\""; }?>>
								<span class="sidebar-icon"><i class="fa fa-line-chart"></i></span>
								<span class="sidebar-title">Stats</span>
							</a>
                        </li>
                        <li>
                            <a class="accordion-toggle collapsed toggle-switch" data-toggle="collapse" href="#submenu" id="selected">
								<span class="sidebar-icon"><i class="fa fa-terminal"></i></span>
								<span class="sidebar-title">Console</span>
								<b class="caret"></b>
							</a>
							<ul id="submenu" class="panel-collapse collapse panel-switch" role="menu">
								<?php
									$index = 0;
									
									foreach ($server_names as $server) {
										echo "<li><a href=\"console.php?name=". $server . "&id=" . $index ."\"><i class=\"fa fa-caret-right\"></i>" .  $server . "</a></li>";
										$index = $index + 1;
									}
								?>
							</ul>
                        </li>
                    </ul>
                </aside>
            </div>

            <!-- change pass modal -->
            <!-- line modal -->
            <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h3 class="modal-title" id="lineModalLabel">Change password</h3>
                        </div>
                        <form class="login-form" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-body">

                                <!-- content goes here -->
                                <form>
                                    <div class="form-group">
                                        <label for="oldpassword">Old password</label>
                                        <input type="password" class="form-control" name="oldpassword" placeholder="Enter old password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newpassword">Current password</label>
                                        <input type="password" class="form-control" name="newpassword" placeholder="Enter new password" required>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" role="button">Close</button>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <button type="submit" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Change</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                    </div>
                </div>
            </div>
			
            <!-- global console modal -->
            <!-- line modal -->
            <div class="modal fade" id="globalConsole" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h3 class="modal-title" id="lineModalLabel">Broadcaster</h3>
                        </div>
                            <div class="modal-body">

                                <!-- content goes here -->
                                    <div class="form-group">
                                        <label >Command or message:</label>
                                        <input type="text" class="form-control" id="globalinput" placeholder="Send command or message">
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" role="button">Close</button>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <button onClick="runGlobalCommand();" class="btn btn-default btn-hover-green">Send</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <!-- main -->
            <main id="page-content-wrapper" role="main">
                <div class="container">
                    <div class="row">
                        <h1 id="serverName" class="text-center">Server name</h1>
                        <br /><br />
                        <div class="col-md-8">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Console
                                </div>
                                <div id="console" class="panel-body">
                                    <ul id="consoleData" class="media-list">
									
                                    </ul>
                                </div>
                                <div class="panel-footer">
                                    <div class="input-group">
                                        <input id="commandLine" type="text" class="form-control" placeholder="Send message or command" />
                                        <span class="input-group-btn">
                                        <button class="btn btn-info" type="button" onclick="runCommand();">Send</button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Online users
                                </div>
                                <div id="players" class="panel-body">
                                    <ul id="playerData" class="media-list">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Status
                                </div>
                                <div id="players" class="panel-body">
                                    <ul id="statusData" class="media-list">
										<li>
											<b>Ram Usage:</b>
											<div class="progress">
											  <div id="ram" class="progress-bar progress-bar-danger active" role="progressbar" aria-valuenow="20" aria-valuemin="10" aria-valuemax="10" style="width: 0%">
											   0%
											  </div>
											</div>
										</li>
										
										<li>
											<b>TPS:</b>
											<div class="progress">
											  <div id="tps" class="progress-bar progress-bar-danger active" role="progressbar" aria-valuenow="20" aria-valuemin="10" aria-valuemax="10" style="width: 0%">
											   0
											  </div>
											</div>
										</li>
										
										<li>
											<b>CPU:</b>
											<div class="progress">
											  <div id="cpu" class="progress-bar progress-bar-danger active" role="progressbar" aria-valuenow="20" aria-valuemin="10" aria-valuemax="10" style="width: 0%">
											   0%
											  </div>
											</div>
										</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				<button type="button" data-toggle="modal" data-target="#globalConsole" class="btn btn-circle btn-xl"><i class="fa fa-globe"></i></button>
            </main>
            </div>

            <!-- cdn js -->
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
            <script type="text/javascript" src="assets/js/notification.js"></script>
            <script type="text/javascript" src="assets/js/reconnecting-websocket.min.js"></script>
			
			<script>
				var servers = <?php echo json_encode($server_address) ?>;

				function getParam(name) {
					name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
					var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
						results = regex.exec(location.search);
					return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
				}
				
				$("#serverName").text(getParam("name"));

				var ip = "ws://" + servers[parseInt(getParam("id"))];
				
				var websocket = new ReconnectingWebSocket(ip);
				websocket.onmessage = function(event) { onMessage(event) };
				websocket.onclose = function(evt) { onClose(event) };
				
				function onClose(event) {
					$('#playerData').empty();
					
					// Reset tps
					$("#tps").animate({
							width: "0%"
						}, 1);
						
					$("#tps").text('0');
					
					// Reset ram
					$("#ram").animate({
						width: "0%"
					}, 1);
						
					$("#ram").text("0 / 0");
					
					// Reset CPU
					$("#cpu").animate({
							width: "0%"
						}, 1);
						
					$("#cpu").text('0%');
				}
				
				
				function onMessage(event) {
					console.log(event.data);
					
					var data = event.data;
					data = data.replace("<", "[");
					data = data.replace(">", "]");
					
					if (data.startsWith("JOIN: ")) {
						var username = data.replace("JOIN: ", "");
						var output="";
						output += "<li id=\"player-" + username + "\" class=\"media\">";
						output += "<div class=\"media-body\">";
						output += "<div class=\"media\">";
						output += "<a class=\"pull-left\" href=\"#\">";
						output += "<img class=\"media-object img-circle\" src=\"https:\/\/minotar.net\/avatar\/" + username + "\" \/>";
						output += "<\/a>";
						output += "<div class=\"media-body\">";
						output += "<h5>" + username + "<\/h5>";
						output += "<\/div>";
						output += "<\/div>";
						output += "<\/div>";
						output += "<\/li>";


						$("#playerData").append(output);
					} else if (event.data.startsWith("LEAVE: ")) {
						var username = event.data.replace("LEAVE: ", "");
						
						$('#player-' + username).remove();
					} else if (event.data.startsWith("TPS: ")) {
						var tps = parseInt(event.data.replace("TPS:", ""));
						var percent = ((tps * 1.0) / 20) * 100;
						
						$("#tps").animate({
							width: percent + "%"
						}, 1);
						
						$("#tps").text(tps);
					} else if (event.data.startsWith("RAM: ")) {
						var data = event.data.replace("RAM: ", "");
						data = data.split(" ");
						
						var used = parseInt(data[0]);
						var total = parseInt(data[1]);
						var percent = ((used * 1.0) / total) * 100;

						$("#ram").animate({
							width: percent + "%"
						}, 1);
						
						$("#ram").text(formatBytes(used) + " / " + formatBytes(total));
					} else if (event.data.startsWith("CPU: ")) {
						var cpu = parseInt(event.data.replace("CPU:", ""));
						
						$("#cpu").animate({
							width: cpu + "%"
						}, 1);
						
						$("#cpu").text(cpu + "%");
					} else if (event.data.startsWith("VERIFY")) {
						websocket.send(<?php echo "'" . $_SESSION['username'] . "<>" . $_SESSION['pass'] . "'"?>);
					} else {
						var output="";
						output += "<li class=\"media\">";
						output += "<div class=\"media-body\">";
						output += "<div class=\"media\">";
						output += "<a class=\"pull-left\" href=\"#\"><\/a>";
						output += "<div class=\"media-body\" >";
						output += "<p>" + data + "</p>";
						output += "<hr\/>";
						output += "<\/div>";
						output += "<\/div>";
						output += "<\/div>";
						output += "<\/li>";
						
						$("#consoleData").append(output);
					}
				}
				
				function runCommand() {
					var value = $("#commandLine").val(); 

					if (value != "") {
						$("#commandLine").val(""); 
						
						websocket.send("COMMAND: " + value);
					}
				}
				
				function runGlobalCommand() {
					var value = $("#globalinput").val(); 

					if (value != "") {
						$("#globalinput").val(""); 
						
						for (var i = 0; i < servers.length; i++) {
							if (i.toString() != getParam("id")) {
								var tempsocket = new ReconnectingWebSocket("ws://" + servers[i]);
								tempsocket.onmessage = function(event) { onTempMessage(event) };
								
								function onTempMessage(event) {
									if (event.data.startsWith("VERIFY")) {
										tempsocket.send(<?php echo "'" . $_SESSION['username'] . "<>" . $_SESSION['pass'] . "'"?>);
										tempsocket.send("COMMAND: " + value);
										tempsocket.close();
									}
								}
							} else {
								websocket.send("COMMAND: " + value);
							}
						}
					}
				}
				
				function formatBytes(a,b){if(0==a)return"0 Bytes";var c=1e3,d=b||2,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f]}
			</script>
            <?php
         if (isset($_POST['oldpassword']) && isset($_POST['newpassword'])) {
			$oldpass = mysqli_real_escape_string($GLOBALS['con'], $_POST['oldpassword']);
         	$newpass = mysqli_real_escape_string($GLOBALS['con'], $_POST['newpassword']);
						
			if (login($_SESSION['username'], $oldpass)) {
				if (changePassword($_SESSION['username'], $oldpass, $newpass)) {
					showNoti("changed");
				} else {
					showNoti("error-login");
				}
			} else {
				showNoti("error-change");
			}
			
			if (!headers_sent()) {
				header('Location:'.$_SERVER['PHP_SELF']);
			 } else {
				 reloadPage();
			 }
		 }
		 ?>
    </body>

    </html>