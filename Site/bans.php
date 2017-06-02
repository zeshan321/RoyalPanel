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
        <link href='assets/css/notification.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/animate.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/bans.css' rel='stylesheet' type='text/css'>

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
                            <a href="users.php">
								<span class="sidebar-icon"><i class="fa fa-users"></i></span>
								<span class="sidebar-title">Users</span>
							</a>
                        </li>
                        <li>
                            <a href="#">
								<span class="sidebar-icon"><i class="fa fa-file-text"></i></span>
								<span class="sidebar-title">Wiki</span>
							</a>
                        </li>
                        <li>
                            <a href="bans.php" id="selected">
								<span class="sidebar-icon"><i class="fa fa-book"></i></span>
								<span class="sidebar-title">Bans</span>
							</a>
                        </li>
                        <li>
                            <a href="#">
								<span class="sidebar-icon"><i class="fa fa-line-chart"></i></span>
								<span class="sidebar-title">Stats</span>
							</a>
                        </li>
						<li>
							<a class="accordion-toggle collapsed toggle-switch" data-toggle="collapse" href="#submenu">
								<span class="sidebar-icon"><i class="fa fa-terminal"></i></span>
								<span class="sidebar-title">Console</span>
								<b class="caret"></b>
							</a>
							<ul id="submenu" class="panel-collapse collapse panel-switch" role="menu">
								<?php
									$index = 0;
									
									foreach ($server_names as $server) {
										echo "<li><a href=\"console.php?name=". $server . "&ip=" . $server_address[$index] ."\"><i class=\"fa fa-caret-right\"></i>" .  $server . "</a></li>";
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
				
            </main>
        </div>

        <!-- cdn js -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/notification.js"></script>
		
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