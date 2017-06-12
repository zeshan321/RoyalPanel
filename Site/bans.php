<?php
session_start();

include 'assets/functions/loginfunctions.php';
include 'assets/functions/generalfunctions.php';

if (!isset($_SESSION['login'])) {
	if ($_SESSION['login'] != true) {
		header("location: login.php");
	}
}

// Check for password change and update permissions
if (!login($_SESSION['username'], $_SESSION['passw'])) {
	header("location: logout.php");
}

if (!hasPermission("view-bans")) {
	header("location: index.php");
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
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

        <!-- custom css -->
        <link href='assets/css/home.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/notification.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/animate.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/console.css' rel='stylesheet' type='text/css'>

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
                            <a href="mwiki.php" <?php if (!(hasPermission("view-pages"))) { echo "id=\"disabled\""; }?>>
								<span class="sidebar-icon"><i class="fa fa-file-text"></i></span>
								<span class="sidebar-title">Pages</span>
							</a>
                        </li>
                        <li>
                            <a href="bans.php" id="selected">
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
							<a class="accordion-toggle collapsed toggle-switch" data-toggle="collapse" href="#submenu" <?php if (!(hasPermission("console"))) { echo "id=\"disabled\""; }?>>
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


            <!-- main -->
            <main id="page-content-wrapper" role="main">
                <div class="container">
					<div class="input-group">
						<div class="input-group-btn search-panel">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<span id="search_concept">Filter by</span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
							  <li><a href="#name">Name</a></li>
							  <li><a href="#uuid">UUID</a></li>
							  <li><a href="#ip">IP</a></li>
							</ul>
						</div>
						<input type="hidden" name="search_param" value="all" id="search_param">         
						<input id="searchinput" type="text" class="form-control" name="x" placeholder="Search for player">
						<span class="input-group-btn">
							<button onclick="search();" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
						</span>
					</div>

					<div id="bancontent">
						<h1 id="playerName" class="text-center">
						<?php
							if(isset($_GET['filter'])  && isset($_GET['value'])) {
								$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
								$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

								echo getMCName($filter, $value);
							}
						?>
						</h1>
						<br>

						<div class="panel-group" id="accordion">
							<div class="panel panel-primary" id="panel1">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-target="#collapseOne" href="#collapseOne">
								  Stats
								</a>
									</h4>

								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<div class="overlay p-y-3">
											<div class="container">
												<div class="row">
													<div class="main_counter_content text-center white-text wow fadeInUp">
														<div class="col-md-3">
															<div class="single_counter p-y-2 m-t-1">
																<i class="fa fa-sign-in m-b-1"></i>
																<h2 class="statistic-counter">
																<?php
																	if(isset($_GET['filter'])  && isset($_GET['value'])) {
																		$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
																		$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

																		echo getStatValueByUUID("LOGIN", getUUID($filter, $value));
																	}
																?>
																</h2>
																<p>Total logins</p>
															</div>
														</div>
														<div class="col-md-3">
															<div class="single_counter p-y-2 m-t-1">
																<i class="fa fa-calendar-o m-b-1"></i>
																<h2 class="statistic-counter">
																<?php
																	if(isset($_GET['filter'])  && isset($_GET['value'])) {
																		$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
																		$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

																		echo getStatValueByUUID("PLAY_TIME", getUUID($filter, $value));
																	}
																?>
																</h2>
																<p>Minutes played</p>
															</div>
														</div>
														<div class="col-md-3">
															<div class="single_counter p-y-2 m-t-1">
																<i class="fa fa-gavel m-b-1"></i>
																<h2 class="statistic-counter">
																<?php
																	if(isset($_GET['filter'])  && isset($_GET['value'])) {
																		$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
																		$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

																		echo getStatValueByUUID("PUNISHMENTS", getUUID($filter, $value));
																	}
																?>
																</h2>
																<p>Total punishments</p>
															</div>
														</div>
														<div class="col-md-3">
															<div class="single_counter p-y-2 m-t-1">
																<i class="fa fa-comments m-b-1"></i>
																<h2 class="statistic-counter">
																<?php
																	if(isset($_GET['filter'])  && isset($_GET['value'])) {
																		$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
																		$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

																		echo getStatValueByUUID("MESSAGES_SENT", getUUID($filter, $value));
																	}
																?>
																</h2>
																<p>Messages sent</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-primary" id="panel2">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-target="#collapseTwo" href="#collapseTwo" class="collapsed">
								  Bans
								</a>
									</h4>

								</div>
								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>Name</th>
													<th>Banned by</th>
													<th>Reason</th>
													<th>Banned on</th>
													<th>Banned until</th>
												</tr>
											</thead>
											<tbody class="searchable">
												<?php
													if(isset($_GET['filter'])  && isset($_GET['value'])) {
														$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
														$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

														foreach (getBans($filter, $value) as $row) {									
															echo "<tr>";
															echo "<td>". getMCName("uuid", $row["uuid"]) . "</td>";
															echo "<td>". $row["banned_by_name"] . "</td>";
															echo "<td>". $row["reason"] . "</td>";
															echo "<td>". getDateValue($row["time"]) . "</td>";
															echo "<td>". getDateValue($row["until"]) . "</td>";
															echo "</tr>";
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="panel panel-primary" id="panel3">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-target="#collapseThree" href="#collapseThree" class="collapsed">
								  Mutes
								</a>
									</h4>

								</div>
								<div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>Name</th>
													<th>Muted by</th>
													<th>Reason</th>
													<th>Muted on</th>
													<th>Muted until</th>
												</tr>
											</thead>
											<tbody class="searchable">
												<?php
													if(isset($_GET['filter'])  && isset($_GET['value'])) {
														$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
														$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

														foreach (getMutes($filter, $value) as $row) {									
															echo "<tr>";
															echo "<td>". getMCName("uuid", $row["uuid"]) . "</td>";
															echo "<td>". $row["banned_by_name"] . "</td>";
															echo "<td>". $row["reason"] . "</td>";
															echo "<td>". getDateValue($row["time"]) . "</td>";
															echo "<td>". getDateValue($row["until"]) . "</td>";
															echo "</tr>";
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="panel panel-primary" id="panel3">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-target="#collapseWarnings" href="#collapseWarnings" class="collapsed">
								  Warnings
								</a>
									</h4>

								</div>
								<div id="collapseWarnings" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>Name</th>
													<th>Warned by</th>
													<th>Reason</th>
													<th>Warned on</th>
													<th>Warned until</th>
												</tr>
											</thead>
											<tbody class="searchable">
												<?php
													if(isset($_GET['filter'])  && isset($_GET['value'])) {
														$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
														$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

														foreach (getWarnings($filter, $value) as $row) {									
															echo "<tr>";
															echo "<td>". getMCName("uuid", $row["uuid"]) . "</td>";
															echo "<td>". $row["banned_by_name"] . "</td>";
															echo "<td>". $row["reason"] . "</td>";
															echo "<td>". getDateValue($row["time"]) . "</td>";
															echo "<td>". getDateValue($row["until"]) . "</td>";
															echo "</tr>";
														}
													}
												?>
											</tbody>
										</table>
									</div>								
								</div>
							</div>
							<div class="panel panel-primary" id="panel3">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-target="#collapseKicks" href="#collapseKicks" class="collapsed">
								  Kick
								</a>
									</h4>

								</div>
								<div id="collapseKicks" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>Name</th>
													<th>Kicked by</th>
													<th>Reason</th>
													<th>Kicked on</th>
													<th>Kicked until</th>
												</tr>
											</thead>
											<tbody class="searchable">
												<?php
													if (isset($_GET['filter']) && isset($_GET['value'])) {
														$filter = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['filter']);
														$value = mysqli_real_escape_string($GLOBALS['lite_con'], $_GET['value']);

														foreach (getKicks($filter, $value) as $row) {									
															echo "<tr>";
															echo "<td>". getMCName("uuid", $row["uuid"]) . "</td>";
															echo "<td>". $row["banned_by_name"] . "</td>";
															echo "<td>". $row["reason"] . "</td>";
															echo "<td>". getDateValue($row["time"]) . "</td>";
															echo "<td>". getDateValue($row["until"]) . "</td>";
															echo "</tr>";
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </main>
        </div>

        <!-- cdn js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/notification.js"></script>
        <script type="text/javascript" src="assets/js/editor.js"></script>
        <script>
			var concept;
			$(document).ready(function(e){
				$('.search-panel .dropdown-menu').find('a').click(function(e) {
					e.preventDefault();
					var param = $(this).attr("href").replace("#","");
					concept = $(this).text();
					$('.search-panel span#search_concept').text(concept);
					$('.input-group #search_param').val(param);
				});
			});

			function getParam(name) {
				name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
				var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
				
				return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			}

			function search() {
				var value = $('#searchinput').val();
				
				if (value != "") {
					window.location.href = "bans.php?value=" + value + "&filter=" + concept;
				}
			}

			if (getParam("value") == "" || getParam("filter") == "") {
				$("#bancontent").hide();
			} else {
				$("#bancontent").show();
			}
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
			
			header("location: users.php");
		 }
		 ?>
    </body>

    </html>