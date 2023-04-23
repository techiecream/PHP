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
<meta name="author" content="">
<meta name="version" content="2.0">
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


<title>ROBINS STATIONERY AND SECRETARIAL BUREAU</title>
</head>
<body>
	<div id="header">
		<h1>ROBINS STATIONERY AND SECRETARIAL BUREAU</h1>
<?php
// Check if user is logged in
if (isset($_SESSION['ShopLogin'])) {
	// Check user level and display appropriate links 
	//Level 1: 
	if ($_SESSION['Level'] == '1') {
	echo '
		<nav class="navbar">
		|&nbsp; &nbsp;<a href="shop.php">HOME</a>	<br/>
		
		|&nbsp; &nbsp;<a href="logout.php">Logout</a>
		</nav>';
	} 
	//Level 2:
	elseif ($_SESSION['Level'] == '2') {
	echo '
		<nav class="navbar">
		|&nbsp; &nbsp;<a href="shop.php">HOME</a>	<br/>

		|&nbsp; &nbsp;<a href="logout.php">Logout</a><br/>
		</nav>';
	}

	//Level 3:   

	elseif ($_SESSION['Level'] == '3') {
	echo '
		<nav class="navbar">
		|&nbsp; &nbsp;<a href="shop.php">HOME</a>	<br/>
		|&nbsp; &nbsp;<a href="logout.php">Logout</a>
		</nav>';
	}

	//Levels 4: 

	elseif ($_SESSION['Level'] == '4') {
	echo '
		<nav class="navbar">
		|&nbsp; &nbsp;<a href="shop.php">HOME</a>	<br/>
		|&nbsp; &nbsp;<a href="?Newshoi"> SHOP MANAGEMENT</a>	<br/>

		|&nbsp; &nbsp;<a href="logout.php">Logout</a>
		</nav>';
	}	

	//Alpha Level also known as level 5


	elseif ($_SESSION['Level'] == '5') {
	echo '
		<nav class="navbar">
		|&nbsp; &nbsp;<a href="shop.php">HOME</a>	<br/>
		|&nbsp; &nbsp;<a href="logout.php">Logout</a>
		</nav>';
	}
}
 
else{
echo '
<nav class="navbar">
<a href="index.php">Home</a>

</nav>';
}


?>
	</div>
	<div id="main">
		<div id="content">
			<!-- Main content goes here -->
			<p>The Best in Class Experience!</p>
<?php
if (isset($_GET["privacy"]))
{
echo "<form>

	<h1>Privacy Policy</h1>
	<p>At Robin's Stationery and Secretarial Bureau, we are committed to protecting the privacy and security of our users' personal information. This privacy policy explains how we collect, use, and protect information that we receive from users who upload files to our website for further processing.</p>

	<h2>Collection of Information</h2>
	<p>When you upload a file to our website, we collect the following information:</p>
	<ul>
		<li>Your name</li>
		<li>Your email address</li>
		<li>The name of the file you uploaded</li>
		<li>The content of the file</li>
	</ul>

	<h2>Use of Information</h2>
	<p>We use the information that we collect from users who upload files to our website for the following purposes:</p>
	<ul>
		<li>To process the file that you uploaded and provide the requested services</li>
		<li>To communicate with you about your file and any related issues or questions</li>
		<li>To improve our website and services</li>
		<li>To comply with any legal obligations</li>
	</ul>

	<h2>Access to Information</h2>
	<p>Access to the information that you upload to our website is restricted to authorized personnel who require access to perform their job duties. We do not share your information with third parties unless required by law or with your explicit consent.</p>

	<h2>Protection of Information</h2>
	<p>We take the security of your personal information seriously and use industry-standard measures to protect it from unauthorized access, disclosure, or misuse. However, no method of transmission over the internet or electronic storage is 100% secure, and we cannot guarantee absolute security.</p>

	<h2>Retention and Deletion of Information</h2>
	<p>We will retain the information that you upload to our website for as long as necessary to provide the requested services and for legitimate business purposes. When we no longer need the information, we will securely delete it from our systems.</p>

	<h2>Your Rights</h2>
	<p>You have the right to request access to, correction of, or deletion of your personal information that we hold. To exercise these rights or to raise any concerns or questions about our privacy practices, please contact us at info@robinsbureau.com</p>

	<h2>Changes to this Privacy Policy</h2>
	<p>We reserve the right to modify this privacy policy at any time, so please review it frequently. If we make material changes to this policy, we will notify you by email or by posting a notice on our website.</p>
</form>";}
?>


			
<form method="POST" action="">
/*  */
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

<div id="twitter-feed">
<!-- Embed Twitter feed here -->
<a class="twitter-timeline" data-width="250" data-height="300" href="https://twitter.com/robinsbureau?ref_src=twsrc%5Etfw">Robins Bureau</a> 
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> 	
<!-- end of Embed Twitter feed here -->
</div>

</div>
</div>
<div id="footer">
    <p>Copyright &copy;  2023 RSSB</p>
	<a href="index.php?privacy">Privacy Policy</a>
    <a href="https://twitter.com/robinsbureau"><i class="fab fa-twitter"></i></a>
    <a href="https://www.telegram.org/robinsbureau/"><i class="fab fa-telegram"></i></a>
</div><!--end #footer -->

</body>

</html>	