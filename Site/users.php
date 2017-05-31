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
        <link href='assets/css/users.css' rel='stylesheet' type='text/css'>
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
					
                    <div class="notification alert alert-danger error-user" role="alert">
                        <span class="fa fa-minus-circle"></span> Unable to create new user!
                    </div>
					
                    <div class="notification alert alert-danger error-update" role="alert">
                        <span class="fa fa-minus-circle"></span> Unable to update user!
                    </div>

                    <div class="notification alert alert-success changed">
                        <span class="fa fa-check-circle"></span> Successfully changed password!
                    </div>
					
                    <div class="notification alert alert-success created">
                        <span class="fa fa-check-circle"></span> Successfully created new user!
                    </div>
					
                    <div class="notification alert alert-success updated">
                        <span class="fa fa-check-circle"></span> Successfully updated user!
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
                            <a href="users.php" id="selected">
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
                            <a href="#">
								<span class="sidebar-icon"><i class="fa fa-book"></i></span>
								<span class="sidebar-title">Logs</span>
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
								<li><a href="#"><i class="fa fa-caret-right"></i>Posts</a></li>
								<li><a href="#"><i class="fa fa-caret-right"></i>Comments</a></li>
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
			
            <!-- add user modal -->
            <!-- line modal -->
            <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h3 class="modal-title" id="lineModalLabel">Create user</h3>
                        </div>
                        <form class="login-form" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-body">

                                <!-- content goes here -->
                                <form>
                                    <div class="form-group">
                                        <label for="addusername">Username</label>
                                        <input type="text" class="form-control" name="addusername" placeholder="Enter username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="addpassword">Password</label>
                                        <input type="password" class="form-control" name="addpassword" placeholder="Enter password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="addmcusername">MC Username</label>
                                        <input type="text" class="form-control" name="addmcusername" placeholder="Enter MC username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="addemail">Email</label>
                                        <input type="email" class="form-control" name="addemail" placeholder="Enter email" required>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" role="button">Close</button>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <button type="submit" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Create</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                    </div>
                </div>
            </div>
			
            <!-- edit user modal -->
            <!-- line modal -->
            <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h3 class="modal-title" id="lineModalLabel">Edit user</h3>
                        </div>
                        <form id="editUserForm" class="login-form" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-body">

                                <!-- content goes here -->
                                    <div class="form-group">
                                        <label for="addusername">Username</label>
                                        <input type="text" class="form-control" name="editusername" placeholder="Enter username" disabled>
										<input type="hidden" class="form-control" name="editusernamehide" placeholder="Enter username">
                                    </div>
                                    <div class="form-group">
                                        <label for="addpassword">Password</label>
                                        <input type="password" class="form-control" name="editpassword" placeholder="Leave blank to not change">
                                    </div>
                                    <div class="form-group">
                                        <label for="addmcusername">MC Username</label>
                                        <input type="text" class="form-control" name="editmcusername" placeholder="Enter MC username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="addemail">Email</label>
                                        <input type="email" class="form-control" name="editemail" placeholder="Enter email" required>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" role="button">Close</button>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <button type="submit" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Update</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                    </div>
                </div>
            </div>

            <!-- main -->
            <main id="page-content-wrapper" role="main">
                <div class="container-fluid">
                    <input id="filter" type="text" class="form-control" placeholder="Search">
					
					<br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>MC Username</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="searchable">
							<?php
								foreach (getAllUsers() as $row) {
									$ingameName = uuid_to_username($row["uuid"]);
									
									echo "<tr>";
									echo "<td>". $row["username"] . "</td>";
									echo "<td> " . $ingameName . "</td>";
									echo "<td>". $row["email"] . "</td>";
									echo "<td><a class=\"btn btn-info btn-xs\" onclick=\"editUser('" . $row["username"] . "', '" . $ingameName ."', '" . $row["email"] . "');\" href=\"#\"><span class=\"fa fa-pencil\"></span> Edit</a> <a href=\"#\" class=\"btn btn-danger btn-xs\"><span class=\"fa fa-trash\"></span> Delete</a></td>";
									echo "</tr>";
								}
							?>
                        </tbody>
                    </table>
                </div>
				
				<button type="button" data-toggle="modal" data-target="#addUser" class="btn btn-circle btn-xl"><i class="fa fa-plus"></i></button>
            </main>
        </div>

        <!-- cdn js -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/notification.js"></script>
        <script>
            // Users search
			$(document).ready(function () {
				(function ($) {

					$('#filter').keyup(function () {

						var rex = new RegExp($(this).val(), 'i');
						$('.searchable tr').hide();
						$('.searchable tr').filter(function () {
							return rex.test($(this).text());
						}).show();

					})

				}(jQuery));

			});
			
			function editUser(username, ingame, email) {
				$('input[name="editusernamehide"]').val(username);
				$('input[name="editusername"]').val(username);
				$('input[name="editmcusername"]').val(ingame);
				$('input[name="editemail"]').val(email);
				
				$('#editUser').modal('show');
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
			
			header("location: users");
		 }
		 
		 if (isset($_POST['addusername']) && isset($_POST['addpassword']) && isset($_POST['addmcusername']) && isset($_POST['addemail'])) {
			 $user = mysqli_real_escape_string($GLOBALS['con'], $_POST['addusername']);
			 $pass = mysqli_real_escape_string($GLOBALS['con'], $_POST['addpassword']);
			 $uuid = mysqli_real_escape_string($GLOBALS['con'], username_to_uuid($_POST['addmcusername']));
			 $email = mysqli_real_escape_string($GLOBALS['con'], $_POST['addemail']);
			 
			 if (createUser($user, $pass, $uuid, $email)) {
				 showNoti("created");
			 } else {
				 showNoti("error-user");
			 }

			 if (!headers_sent()) {
				header('Location:'.$_SERVER['PHP_SELF']);
			 } else {
				 reloadPage();
			 }
		 }
		 
		 if (isset($_POST['editusernamehide']) && isset($_POST['editmcusername']) && isset($_POST['editemail'])) {
			 $user = mysqli_real_escape_string($GLOBALS['con'], $_POST['editusernamehide']);
			 $pass = mysqli_real_escape_string($GLOBALS['con'], $_POST['editpassword']);
			 $uuid = mysqli_real_escape_string($GLOBALS['con'], username_to_uuid($_POST['editmcusername']));
			 $email = mysqli_real_escape_string($GLOBALS['con'], $_POST['editemail']);

			 if (updateUser($user, $pass, $uuid, $email)) {
				 showNoti("updated");
			 } else {
				 showNoti("error-update");
			 }
			 
			 if (!headers_sent()) {
				header('Location:'.$_SERVER['PHP_SELF']);
			 } else {
				 //reloadPage();
			 }
		 }
		 ?>
    </body>

    </html>