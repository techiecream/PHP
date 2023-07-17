<?php
//Start session
session_start();
// Connect to the database
$servername = "localhost"; // server name
$username = " "; // server user name
$password = " ";// server user name password
$dbname = " ";// server user name
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="ROBINS BUREAU">
<meta name="version" content="2.1.0">
<link rel = "stylesheet" type= "text/css" href = "style.css" media= "screen"/>
<script src="jquery.js"></script>

<?php
// Check if the user is already logged in
if (isset($_SESSION["ShopLogin"]) && $_SESSION["ShopLogin"] === true) {
	// If the user is already logged in, redirect them to the dashboard
	header("Location: shop.php");
	exit;
}

// Check if the login form has been submitted
if (isset($_POST['do']) && $_POST['do'] === 'agrant') {

    // Sanitize input data
    $selID = filter_input(INPUT_POST, 'ticflid', FILTER_SANITIZE_STRING);
    $selpsw = filter_input(INPUT_POST, 'ticflpsw', FILTER_SANITIZE_STRING);

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, "SELECT * FROM shopusers WHERE username=? AND AccountStatus='Active'");
    mysqli_stmt_bind_param($stmt, 's', $selID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch user data
    $user = mysqli_fetch_assoc($result);

    // Verify password
	   $sue=$selpsw.$user['PassSalt'];
        if ($user['username'] === $selID  && $user['password']===hash("sha512",$sue)){
        // Start a new session
        session_regenerate_id(true);
        $_SESSION['ShopLogin'] = true;
        $_SESSION['selID'] = $user['email'];
        $_SESSION['SAdmin'] = $user['username'];
        $_SESSION['Level'] = $user['CLOR'];

        // Set secure cookie parameters
        session_set_cookie_params([
            'lifetime' => 86400,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        // Redirect the user to the dashboard
        if (isset($_SESSION['login_redirect'])) {
            header('Location: ' . $_SESSION['login_redirect']);
            unset($_SESSION['login_redirect']);
        } else {
            header('Location: shop.php');
        }
        exit;
    } else {
        // Display error message
		$error_msg = "Incorrect username or password. Please try again.";
    }
} else {
    echo ''; // No action needed
}
?>


<title>MY SHOP</title>
</head>
<body>
	<div id="header">
		<h1>MY BUSINESS</h1>
	</div>
	<div id="main">
		<div id="content">
			<!-- Main content goes here -->
		
<form method="POST" action="">
<!-- Login form -->
<h1>Login</h1>
<input type="text" name="ticflid" placeholder="Username" id="ticflid" required>
<input type="password" name="ticflpsw" placeholder="Password" id="ticflpsw" required>
<input type="hidden" name="do" value="agrant">
<input type="submit" value="Login">
<!-- Login form erros or any other errors-->
<?php if(isset($error_msg)) { ?>
<p class="error"><?php echo $error_msg; ?></p>
<?php } ?>		
</form>
</div>	
<div id="sidebar">
</div>
</div>
<div id="footer">
<p>Copyright &copy;  2022 - <?php echo date('Y') ?>  Developed by <a target="_blank" href="https://www.twitter.com/robinsbureau">ROBINS BUREAU</a></b> All rights reserved.
</p>

</div><!--end #footer -->
</body>
</html>	