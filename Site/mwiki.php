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

if (!hasPermission("view-pages")) {
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
        <link href='assets/css/users.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/notification.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/animate.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/editor.css' rel='stylesheet' type='text/css'>

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
                            <a href="mwiki.php" id="selected">
								<span class="sidebar-icon"><i class="fa fa-file-text"></i></span>
								<span class="sidebar-title">Pages</span>
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

            <!-- edit page modal -->
            <!-- line modal -->
            <div class="modal fade" id="editPage" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h3 class="modal-title" id="lineModalLabel">Edit page</h3>
                        </div>
                        <form name="editForm" class="login-form" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="modal-body">

                                <!-- content goes here -->
								<input type="hidden" name="editid">

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Enter title" required>
                                </div>

                                <div class="form-group">
                                    <label for="settings">Settings</label>
                                    <input type="text" class="form-control" name="settings" placeholder="Enter settings">
									
									<a href="#settings" data-toggle="collapse">Click to view options</a>
									<div id="settings" class="collapse">
										<p>All settings must be seperated by spaces.</p>
										<p>'All' will allow anyone with the link to see the page.</p>
										<p>'Signed' will allow all users with Willow account to see the page.</p>
										<p>'View' will allow all users signed in with the 'view pages' permission.</p>
									</div>
                                </div>

                                <div class="form-group">
                                    <label for="editor">Editor</label>
                                    <textarea id="editor" name="editor"></textarea>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" role="button">Close</button>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <button type="submit" class="btn btn-default btn-hover-green" data-action="save" role="button">Create/update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- delete modal -->
            <div class="modal fade" id="deletePage" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            <h3 class="modal-title" id="lineModalLabel">Delete page</h3>
                        </div>
                        <div class="modal-body">

                            <!-- content goes here -->
                            <form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
								<h4 id="deleteinfo"></h4>
								
                                <div class="form-group">
                                    <input type="hidden" name="deleteid">
                                 </div>

                        </div>
						
                        <div class="modal-footer">
                            <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" role="button">Close</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="submit" class="btn btn-default">Submit</button>
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
                    <input id="filter" type="text" class="form-control" placeholder="Search">

                    <br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Last edited by</th>
                                <?php
								if (hasPermission("edit-page") || hasPermission("delete-page")) {
									echo "<th>Actions</th>";
								}
								?>
                            </tr>
                        </thead>
                        <tbody class="searchable">
                            <?php
								foreach (getWikiPages() as $row) {									
									echo "<tr>";
									echo "<td><a target=\"_blank\" href=\"viewer.php?id=" . $row["id"] . "\">". $row["title"] . "</a></td>";
									echo "<td>". $row["owner"] . "</td>";
									
									if (hasPermission("edit-page") || hasPermission("delete-page")) {
										echo "<td>";
										if (hasPermission("edit-page")) {
											echo "<a class=\"btn btn-info btn-xs\" onclick=\"editWiki('" . $row["title"] . "'," . $row["id"] . ");\" href=\"#\"><span class=\"fa fa-pencil\"></span> Edit</a>";
										}
										
										if (hasPermission("delete-page")) {
											echo " <a class=\"btn btn-danger btn-xs\" onclick=\"deleteWiki('" . $row["title"] . "'," . $row["id"] . ");\" href=\"#\"><span class=\"fa fa-pencil\"></span> Delete</a>";
										}
										echo "</td>";
									}
									
									echo "</tr>";
								}
							?>
                        </tbody>
                    </table>
                </div>

                <?php
					if (hasPermission("create-pages")) {
						echo "<button type=\"button\" data-toggle=\"modal\" data-target=\"#editPage\" class=\"btn btn-circle btn-xl\"><i class=\"fa fa-plus\"></i></button>";
					}
				?>
            </main>
        </div>

        <!-- cdn js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/notification.js"></script>
        <script type="text/javascript" src="assets/js/editor.js"></script>
        <script>
			function getParam(name) {
				name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
				var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
				
				return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			}
				
            $(document).ready(function() {
                (function($) {

                    $('#filter').keyup(function() {

                        var rex = new RegExp($(this).val(), 'i');
                        $('.searchable tr').hide();
                        $('.searchable tr').filter(function() {
                            return rex.test($(this).text());
                        }).show();

                    })

                }(jQuery));

            });

            $(document).ready(function() {
                $("#editor").Editor();
				
				// Show editor
				var id = getParam("id");
				if (id != "") {
					var data = <?php if (isset($_GET['id'])) { echo json_encode(getWikiByID(mysqli_real_escape_string($GLOBALS['con'],  $_GET['id']))); } ?>
					
					$('input[name="editid"]').val(id);
					$('input[name="title"]').val(data["title"]);
					$('input[name="settings"]').val(data["settings"]);
					$("#editor").Editor("setText", data["content"]);

					$('#editPage').modal('show');
					
					$('#editPage').on('hidden.bs.modal', function () {
						window.location = window.location.href.split("?")[0];
					});
				}
            });
			
            $(document).submit(function() {
                $("#editor").val($("#editor").Editor("getText"));
            });

            function editWiki(title, id) {
				window.location.href = "mwiki.php?id=" + id;
            }

            function deleteWiki(title, id) {
				$('input[name="deleteid"]').val(id);
				$("#deleteinfo").text("Are you sure you want to delete '" + title + "'?");
				
				$('#deletePage').modal('show');
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
		 
		 if (isset($_POST['title']) && isset($_POST['editor']) && isset($_POST['settings'])) {
			$title = mysqli_real_escape_string($GLOBALS['con'], $_POST['title']);
         	$settings = mysqli_real_escape_string($GLOBALS['con'], $_POST['settings']);
         	$content = mysqli_real_escape_string($GLOBALS['con'], $_POST['editor']);
			 
			 if (isset($_POST['editid']) && !empty($_POST['editid'])) {
				$id = mysqli_real_escape_string($GLOBALS['con'], $_POST['editid']);
				editWikiPage($id, $title, $_SESSION['username'], $settings, $content);
			 } else {
				createWikiPage($title, $_SESSION['username'], $settings, $content);
			 }
			 
			 if (!headers_sent()) {
				header('Location:'. explode("?", $_SERVER['PHP_SELF'])[0]);
			 } else {
				echo "<script>window.location = window.location.href.split(\"?\")[0];</script>";
			 }
		 }
		 
		 if (isset($_POST['deleteid'])) {
			$id = mysqli_real_escape_string($GLOBALS['con'], $_POST['deleteid']);
			deleteWikiPage($id);
			
			if (!headers_sent()) {
				header('Location:'.$_SERVER['PHP_SELF']);
			 } else {
				 reloadPage();
			 }
		 }
		 ?>
    </body>

    </html>