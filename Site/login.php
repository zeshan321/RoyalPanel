<?php
   session_start();

   include 'assets/functions/loginfunctions.php';
   include 'assets/functions/generalfunctions.php';
   
   if (isset($_SESSION['login'])) {
	if ($_SESSION['login'] == true) {
		header("location: index");
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
        <link href='assets/css/styles.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/notification.css' rel='stylesheet' type='text/css'>
        <link href='assets/css/animate.css' rel='stylesheet' type='text/css'>

        <!-- icon -->
        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />

    </head>

    <body>

        <div class="background-image"></div>

        <!-- Notifications start -->
        <div class="container">
            <div class="row">
                <div class="com-md-12">
                    <div class="notification alert alert-danger error-login" role="alert">
                        <span class="fa fa-minus-circle"></span> Invalid username or password!
                    </div>
					
					<div class="notification alert alert-success logedin">
						<span class="fa fa-check-circle"></span> Successfully loged in!
					</div>
                </div>
            </div>
        </div>
        <!-- Notifications end -->

        <div class="login-page">
            <div class="form">
                <h1>Willow</h1>
                <br>
                <form class="login-form" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <input type="text" name="user" placeholder="Username" />
                    <input type="password" name="pass" placeholder="Password" />
                    <button type="submit">login</button>
                </form>
            </div>
        </div>

        <!-- cdn js -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/js/notification.js"></script>
		
		<?php
         if (isset($_POST['user']) && isset($_POST['pass'])) {
			$user = mysqli_real_escape_string($GLOBALS['con'], $_POST['user']);
         	$pass = mysqli_real_escape_string($GLOBALS['con'], $_POST['pass']);
						
			if (login($user, $pass)) {
				$_SESSION['login'] = true;
				$_SESSION['username']= $user;
				
				showNoti("logedin");
				header("location: index");
			} else {
				showNoti("error-login");
			}
			
			header("location: login");
		 }
		 ?>
    </body>

    </html>