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
<title>ROBINS STATIONERY AND SECRETARIAL BUREAU</title>

<?php
//Start session
session_start();

//Check whether the session variable SESS_MEMBER_ID is present or not
if (isset($_SESSION['ShopLogin']) && $_SESSION['ShopLogin'] == true) {
    echo "";
}
else {
	// In the pages that redirect to login.
	$_SESSION["login_redirect"] = $_SERVER["PHP_SELF"];
	header("Location: index.php");
	exit;
}
// Connect to the database
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rosca";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
//sales
// Check if the "store" POST variable is set and equals "NT"
if (isset($_POST["store"]) && $_POST["store"] == "NT") {
  // Escape and sanitize user input
  $item = ucwords(strtolower(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfeyc", FILTER_SANITIZE_STRING))));
  $amount = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflanom", FILTER_SANITIZE_STRING));
  $remarks = ucwords(strtolower(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticffinom", FILTER_SANITIZE_STRING))));
  $balance = $_SESSION['SAdmin'];
  $qtysold = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflaqty", FILTER_SANITIZE_STRING));
  $today = date('Y-m-d');
  // Construct query using prepared statement
  $query = "INSERT INTO shopsales (Date, Item, QtySold, cost, Remark, Handler) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "ssdsss", $today, $item, $qtysold, $amount, $remarks, $balance);
  // Execute query
  if (mysqli_stmt_execute($stmt)) {
      // Success
    $smsg="Transaction Added successfully";    
  } else {
      // Error
      $msg="Error: ".mysqli_error($conn);
  }
} else {
    echo " ";
}
 
//todo
// Check if the "todo" POST variable is set and equals "actoNT"
if (isset($_POST["todo"]) && $_POST["todo"] == "actoNT") {
  // Get current date
  $today = date('d-m-Y');

  // Escape and sanitize user input
  $aa = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfeyc", FILTER_SANITIZE_STRING)); //task
  $bb = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflanom", FILTER_SANITIZE_STRING)); //Due date
  $cc = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticffinom", FILTER_SANITIZE_STRING)); //remarks
  $dd = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflbnom", FILTER_SANITIZE_STRING)); // status
  $ss = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfiot", FILTER_SANITIZE_STRING)); // user

  // Construct query
  $query = "INSERT INTO shoptasks (Sno, DueDate, Activity, Remarks, Status, Incharge) VALUES (Sno, '$bb', '$aa', '$cc', '$dd', '$ss')";

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	$smsg= "Task Added  successfully";

  } else {
      // Error
      $msg=  "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}

//Register new system user
// Check if the "dno" POST variable is set and equals "NM"
if (isset($_POST["dno"]) && $_POST["dno"] == "NM") {
  // Escape and sanitize user input
$nsurn=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfffnom", FILTER_SANITIZE_STRING));// User name
$nsure=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfblo", FILTER_SANITIZE_STRING));// Email Address
$nsurp=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticffinom", FILTER_SANITIZE_STRING));// Password
$nsurc=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflnnom", FILTER_SANITIZE_STRING));// Contact Number
$nsuen=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflenom", FILTER_SANITIZE_STRING));// Employee Number
$nsuas=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflasom", FILTER_SANITIZE_STRING));// Account Status
$nsuet=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfletom", FILTER_SANITIZE_STRING));// Employee level
$ss=$chars = "abcdefghjkmnpqrtwxyABCDEFGHJKLMNPQRTUVWXYZ0123456789_-"; //set characters for salt
$salt = substr( str_shuffle( $chars ), 0, 64 ); // characters in salt
$password = hash("sha512",$nsurp.$salt);//encrypt the password before saving in the database
  // Construct query
  $query="INSERT INTO shopusers (id, username, email, password, PassSalt, contact, CLOR, AccountStatus, AccountTerminationDate, Namba, AppointedOn) VALUES(id,'$nsurn','$nsure','$password','$salt','$nsurc','$nsuet','$nsuas','0000-00-00 00:00:00','$nsuen','2022-02-15')";

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success

	  $smsg= "User Account Created Successfully";

  } else {
      // Error
      $msg= "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}



//new user registration
if (isset($_POST["dcno"])=="DCT"){
	$today = date('Y-m-d');
$aa=ucwords(strtoupper($_POST["dcoder"])); # Receiver
$kk=ucwords(strtoupper($_POST["dcogive"])); # Code
$konsc=time()*10;
$today = date('Y-m-d');
$query="insert into dtrackers value('$konsc','$today','$kk','$aa','')";
mysqli_query($conn,$query);
  }
//user reset password
if (isset($_GET["userpare"])) {
  $bb="Reset001"; // New Password
  $uatbr=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "userpare", FILTER_SANITIZE_STRING)); // User
  $query="SELECT * FROM shopusers WHERE id='$uatbr'";
  $result=mysqli_query($conn, $query);
  // a user does not already exist with the same username and/or email
  $user = mysqli_fetch_assoc($result);  
  //echo $user;
  if ($user) { // if user exists
    if ($user['id'] === $uatbr) {
      $salt = substr(str_shuffle("abcdefghjkmnpqrtwxyABCDEFGHJKLMNPQRTUVWXYZ0123456789_-"), 0, 64); // set characters for salt
      $password = hash("sha512",$bb.$salt);//encrypt the password before saving in the database
      $query = "UPDATE shopusers SET password ='$password', PassSalt='$salt' WHERE id ='$uatbr'";
      mysqli_query($conn, $query);
    } else {
      echo "Bad";
    }
  } else {
    echo "Not found";
  }
}

 else {
  echo " ";
}

//user termination 
if (isset($_GET["userparet"])) {

  $uatbr=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "userparet", FILTER_SANITIZE_STRING)); // User
  $query="SELECT * FROM shopusers WHERE id='$uatbr'";
  $result=mysqli_query($conn, $query);
  
  // a user does not already exist with the same username and/or email
  $user = mysqli_fetch_assoc($result);  
  //echo $user;
  if ($user) { // if user exists
    if ($user['id'] === $uatbr) {
		$h=date('Y-m-d H:i:s');
      $query = "UPDATE shopusers SET AccountTerminationDate ='$h', AccountStatus='TERMINATED' WHERE id ='$uatbr'";
      mysqli_query($conn, $query);
    } else {
      echo "Bad";
    }
  } else {
    echo "Not found";
  }
}

 else {
  echo " ";
}

//user update 
if (isset($_POST["userparea"]) && $_POST["userparea"] == "userchudsu") {
	$susn=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudun", FILTER_SANITIZE_STRING)); // Username
	$suse=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudue", FILTER_SANITIZE_STRING)); // email
	$susc=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudupn", FILTER_SANITIZE_STRING)); // contact
	$susl=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudlor", FILTER_SANITIZE_STRING)); // CLOR
	$susa=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudac", FILTER_SANITIZE_STRING)); // account status
	$susui=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudei", FILTER_SANITIZE_STRING)); // employee number
	$sususi=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "userchudsid", FILTER_SANITIZE_STRING)); // system id
  // Construct query
  $query =  "UPDATE shopusers SET username='$susn',email='$suse', contact='$susc', CLOR='$susl', AccountStatus='$susa', Namba='$susui' WHERE id ='$sususi'";
  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	  $smsg= "User Profile Updated successfully";

  } else {
      // Error
      $msg= "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}


 //completed list of task
if (isset($_GET["cdiscot"]))
{
$tskid=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "cdiscot", FILTER_SANITIZE_STRING)); // Status
$sql = "UPDATE dtrackers SET status ='Claimed' WHERE Sno ='$tskid'";
mysqli_query($conn,$sql);
header("location: shop.php");
echo "";
}

//updating debtors debts
// Check if the "pymdt" POST variable is set and equals "Nedt"
if (isset($_POST["pymdt"]) && $_POST["pymdt"] == "Nedt") {
  // Get current date
  $today = date('Y-m-d');
  // Escape and sanitize user input
  
  $aa = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "dbtnm", FILTER_SANITIZE_STRING)); //debtors details name
  $bb = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "blpdt", FILTER_SANITIZE_STRING)); //Updated balance
  $cc = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "amtpd", FILTER_SANITIZE_STRING)); //Amount paid 
  $dd = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "pymdtid", FILTER_SANITIZE_STRING)); //Transaction id
  $ff = $aa." and paid ".$cc. " on ".$today ;

  // Construct query
  $query = "UPDATE shopdebtors SET Balance ='$bb', Remark ='$ff', Cost ='$cc' WHERE Sno ='$dd'";
  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	  $queery="select * from shopdebtors where Sno ='$dd'";

	  $result=mysqli_query($conn, $queery);
	  while ($row = mysqli_fetch_array($result)) {
		  $aaaa=$row[2];//item
		  $bbbb=$row[3];//quantity sold
		  $cccc=$row[4];//cost
		  $dddd=$row[5];//remark
		}
		
		$today = date('Y-m-d');
		$balance = $_SESSION['SAdmin'];
		$milky = "INSERT INTO shopsales (Sno, Date, Item, QtySold, cost, Remark, Handler) VALUES (Sno, '$today', '$aaaa', '$bbbb', '$cccc', '$dddd', '$balance')";
		mysqli_query($conn, $milky);
	$smsg= "Balance Updated successfully";		
  } else {
      // Error
      $msg= "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}

//updating transactions profiles
// Check if the "URAAC" POST variable is set and equals "URARC"
if (isset($_POST["assutr"]) && $_POST["assutr"] == "arssutr") {

  // Escape and sanitize user input
  $item = ucwords(strtolower(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "nsutr", FILTER_SANITIZE_STRING))));
  $amount = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "asutr", FILTER_SANITIZE_STRING));
  $remarks = ucwords(strtolower(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "dsutr", FILTER_SANITIZE_STRING))));
  $balance = $_SESSION['SAdmin'];
  $qtysold = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "qsutr", FILTER_SANITIZE_STRING));
  $today = date('Y-m-d');
  $recor = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "tbsutr", FILTER_SANITIZE_STRING));
  $res = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "rsutr", FILTER_SANITIZE_STRING));

  // Construct query using prepared statement
  $query = "INSERT INTO salesgw (tranid, Date, Item, QtySold, cost, Remark, CReason) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "sssssss", $recor, $today, $item, $qtysold, $amount, $remarks, $res);

  // Execute query
  if (mysqli_stmt_execute($stmt)) {

      // Construct update query using prepared statement
      $squery = "UPDATE shopsales SET Item = ?, QtySold = ?, cost = ?, Remark = ? WHERE Sno = ?";
      $sstmt = mysqli_prepare($conn, $squery);
      mysqli_stmt_bind_param($sstmt, "ssssi", $item, $qtysold, $amount, $remarks, $recor);

      // Execute update query
      if (mysqli_stmt_execute($sstmt)) {
          // Success
          $smsg= "Sales Updated successfully";
      } 
      else {
          // Error
          $msg= "Error: " . mysqli_error($conn);
      }
      
      // Success
      $smsg="Transaction Added successfully";    
  } else {
      // Error
      $msg="Error: ".mysqli_error($conn);
  }
} else {
    echo " ";
}


//DEBTORS
// Check if the "shdet" POST variable is set and equals "NSDT"
if (isset($_POST["shdet"]) && $_POST["shdet"] == "NSDT") {
  // Escape and sanitize user input
  $item = ucwords(strtolower(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfeyc", FILTER_SANITIZE_STRING))));
  $amount = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflanom", FILTER_SANITIZE_STRING));
  $remarks = ucwords(strtolower(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticffinom", FILTER_SANITIZE_STRING))));
  $balance = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflanom", FILTER_SANITIZE_STRING));
  $qtysold = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflaqty", FILTER_SANITIZE_STRING));
  $today = date('Y-m-d');
  // Construct query
  $query = "INSERT INTO shopdebtors (Sno, Date, Item, QtySold, cost, Remark, Balance) VALUES (Sno, '$today', '$item', '$qtysold', '$amount', '$remarks', '$balance')";

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	$smsg= "Debtor Details Added successfully";

  } else {
      // Error
      $msg= "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}
//update product details
// Check if the "shspc" POST variable is set and equals "shspce"
if (isset($_POST["shspc"]) && $_POST["shspc"] == "shspce") {
  // Escape and sanitize user input
  $today = date('Y-m-d');
  $aspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "spbe", FILTER_SANITIZE_STRING)); //Product Name
  $bspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "spbpp", FILTER_SANITIZE_STRING)); //Bulk Purchase Price
  $cspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "spbqp", FILTER_SANITIZE_STRING)); //Bulk Quantity Bought
  $dspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "spcs", FILTER_SANITIZE_STRING)); //Status
  $espe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "spcn", FILTER_SANITIZE_STRING)); //Notes
  $fspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "spbeid", FILTER_SANITIZE_STRING)); // ID
	$pd=$bspe/$cspe; //buying price
	$sc=round($pd+(0.5*$pd),-2); //selling price
	$cip=$espe." Last updated on ".$today;

  // Construct query
  $query = " UPDATE shopitems SET Item='$aspe', BulkPurchasePrice='$bspe', BulkPurchaseQty='$cspe', Buying='$cip', Selling='$sc', Status='$dspe' WHERE Sno ='$fspe'";

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	  	  $smsg= "Product Updated successfully";
	 
  } else {
      // Error
      echo "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}

 
//adding shop expense
// Check if the "shopcex" POST variable is set and equals "ShopExp"
if (isset($_POST["shopcex"]) && $_POST["shopcex"] == "ShopExp") {
  // Escape and sanitize user input
  $sa = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfnen", FILTER_SANITIZE_STRING)); //item
  $sb = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfeq", FILTER_SANITIZE_STRING)); //Qty
  $sc = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfea", FILTER_SANITIZE_STRING)); //Amt
  $sd = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfer", FILTER_SANITIZE_STRING)); //remarks
  $today = date('Y-m-d');

  // Construct query
  $query = "INSERT INTO shopexpenses (Sno, Date, Item, Qty, Cost, Remark)  VALUES (Sno,'$today','$sa','$sb','$sc','$sd')";

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	  $smsg= "Expense Added successfully";

  } else {
      // Error
      $msg= "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}

//update expense details
// Check if the "shopcex" POST variable is set and equals "shsece"
if (isset($_POST["shepc"]) && $_POST["shepc"] == "shsece") {
  // Escape and sanitize user input
  $today = date('Y-m-d');
  $easpe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "sebe", FILTER_SANITIZE_STRING)); //Name
  $ebspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "sebpp", FILTER_SANITIZE_STRING)); //Qty
  $ecspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "sebqp", FILTER_SANITIZE_STRING)); //Amt
  $edspe = ucwords(strtoupper(mysqli_real_escape_string($conn, filter_input(INPUT_POST, "sepcs", FILTER_SANITIZE_STRING)))); //remarks
  $efspe = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "sebeid", FILTER_SANITIZE_STRING)); //id
$cip=" Updated on ".$today;

  // Construct query
$query = " UPDATE shopexpenses SET Item='$easpe', Qty='$ebspe', Cost='$ecspe', Remark='$edspe' WHERE Sno ='$efspe'";
  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	  echo "<table style='border:0px solid silver' cellpadding='5' cellspacing='0' align='center' border='0' >
	  <tr><td colspan='4' align='center'>Expense Updated successfully</td></tr></table>";

  } else {
      // Error
      echo "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
} 

//adding shop items
// Check if the "todo" POST variable is set and equals "actoNT"
if (isset($_POST["shitodo"]) && $_POST["shitodo"] == "ShiNT") {
  // Get current date
	$today = date('d-m-Y');
  // Escape and sanitize user input
  $sa = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfnsi", FILTER_SANITIZE_STRING)); //item
  $sb = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflnsbp", FILTER_SANITIZE_STRING)); //Buying Price
  $sc = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfnssp", FILTER_SANITIZE_STRING)); //Selling Price
  $sd = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflnsmt", FILTER_SANITIZE_STRING)); //Monthly Target
  $sf = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticfnspfa", FILTER_SANITIZE_STRING)); //bulk purchase price
  $sg = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "ticflnsqb", FILTER_SANITIZE_STRING)); //Quantity bought
  $pd=$sf/$sg; //buying price
  $sc=round($pd+(0.5*$pd),-2); //selling price
  $today = date('d-m-Y');
  // Construct query
  $query = "INSERT INTO shopitems (Sno, Date, Item, BulkPurchasePrice, BulkPurchaseQty, Buying,Selling,Target,Status) VALUES (Sno,'$today','$sa','$sf','$sg','$sb','$sc','$sd','Active')";

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	$smsg= "Product Added successfully";

  } else {
      // Error
    $msg= "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
}
//mark task as done
// Check if the "done" GET variable is set
if (isset($_GET["done"])) {
  // Escape and sanitize user input
  $tskid=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "done", FILTER_SANITIZE_STRING)); //Assigned Task ID
  // Construct query
  $query = "UPDATE shoptasks SET Status ='Complete' WHERE Sno ='$tskid'"; 

  // Execute query
  if (mysqli_query($conn, $query)) {
      // Success
	  	  echo "<table style='border:0px solid silver' cellpadding='5' cellspacing='0' align='center' border='0' >
	  <tr><td colspan='4' align='center'>Congulations </td></tr></table>";
	  header("Location: shop.php");
  } else {
      // Error
      echo "Error: " . mysqli_error($conn);
  }
} else {
    echo " ";
} 
//new user password
// Check if the "pcsu" POST variable is set and equals "sepec"
if (isset($_POST["pcsu"]) && $_POST["pcsu"] == "NPC") {
$aa = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "oups", FILTER_SANITIZE_STRING));# Old Password
$bb = mysqli_real_escape_string($conn, filter_input(INPUT_POST, "nupc", FILTER_SANITIZE_STRING));# New Password
$cc=$_SESSION['SAdmin']; # User
$query="SELECT * FROM shopusers WHERE username='$cc'";
$result=mysqli_query($conn,$query);
// a user does not already exist with the same username and/or email
$user = mysqli_fetch_assoc($result);  
  //echo $user;
    if ($user) { // if user exists
		if ($user['username'] === $cc) {
			$sue=$aa.$user['PassSalt'];
			$userpassc=hash("sha512",$sue);
			 if ($userpassc===$user['password']){
				 $ss=$chars = "abcdefghjkmnpqrtwxyABCDEFGHJKLMNPQRTUVWXYZ0123456789_-"; //set characters for salt
				 $salt = substr( str_shuffle( $chars ), 0, 64 ); // characters in salt
				 $password = hash("sha512",$bb.$salt);//encrypt the password before saving in the database
				 $query = "UPDATE shopusers SET password ='$password', PassSalt='$salt' WHERE Username ='$cc'";
				mysqli_query($conn, $query);
				header("Location: index.php");
			 }
			 else{
				 echo "Bad";
			 }
		}
		else
		{
			echo "Not found";
		}
	}
	else{
		echo "Not Valid";
	}
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
			<!-- Errors or any other errors-->
  		<?php if(isset($smsg)) { ?>
			<p class="success"><?php echo $smsg; ?></p>
				<?php } elseif(isset($msg)) { ?>
				<p class="error"><?php echo $msg; ?></p>
				<?php } ?>			
<?php
if (isset($_SESSION['ShopLogin']) && $_SESSION['ShopLogin'] == true && $_SESSION['Level'] == '4') {
	echo "
	<form>
	<table>
	<tr><td>
	Hi,
	<a href='shop.php?myprofile#newUser'>".$_SESSION['SAdmin']."</a>". date("F j, Y, g:i a", time() + (3600*3))."</a>
	</td></tr>";
	//check if the date is 16th 
	if (date("d") == "16")
	{
		$FF = "BT".date("dmY").".xls";
		$query="select * from backup where BackupFileName = '$FF' AND BackupStatus = '0'";
		$result=mysqli_query($conn, $query);
		if($result) {
			if(mysqli_num_rows($result) > 0) {
				echo "";
				}
				else {
					echo "<tr><td> <a href='BusinessTransactions.php'>Analyse Business</a></td></tr>";
					}
			}
		else {
			echo "SQL Error";
			}
	}
else 
	{
		echo "";
	}

  // Check if it is Friday and backup 
  if (date("l") == "Friday") {
	  // Check if a backup has already been performed today
    $ddd = date("dmY");
    $query = "SELECT * FROM backup WHERE BackupDate = '$ddd' AND BackupStatus = '0'";
    $result = mysqli_query($conn, $query);
    if ($result) {
      // If a backup has already been performed, do nothing
      if (mysqli_num_rows($result) > 0) {
        echo "";
      }
      // If a backup has not been performed, display a link to perform the backup
      else {
        echo "<tr><td><a href=Databasebackup.php>Backup Now</a></td></tr>";
      }
    } else {
      // If the query was unsuccessful, display an error message
      echo "Error executing query: " . mysqli_error($conn);
    }

  } else {
    // If it is not Friday, do nothing
    echo "";
  }
echo "</table></form>";
}

else{
	echo "";
}

if (isset($_SESSION['ShopLogin']) && $_SESSION['ShopLogin'] == true && ( !($_SESSION['Level'] === 4) ) ) 
{

	echo "
		<form>
	<table>
	<tr><td>
	Hi, 
	
	<a href='shop.php?myprofile#newUser'>".$_SESSION['SAdmin']."</a>". date("F j, Y, g:i a", time() + (3600*3))."
	
	</td></tr>";
	echo "</table></form>";

}
?>
<?php
	//user details
if (isset($_GET["myprofile"]))
{
echo '
<form>
<table >
<tr><th>USER PROFILE</th></tr>';
$cc=$_SESSION['SAdmin']; # User
$query="SELECT SUM(cost) AS Madea FROM shopsales where Handler='$cc'";
$result= mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result); 
$mssum = $row['Madea'];
$query="select * from shopusers where username ='$cc'";
$result=mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
	print "<tr><td> Since: ".$row[10]."</td></tr>";
	print "<tr><td> Employee ID: ".$row[9]."</td></tr>";
	print "<tr><td> User Name: ".$row[1]."</td></tr>";
	print "<tr><td> Email Address: ".$row[2]."</td></tr>";
	print "<tr><td> Phone Number: ".$row[5]."</td></tr>";
		print "<tr><td> CLOR: ".$row[6]."</td></tr>";
	if ($_SESSION['Level'] < 5) {
		print "<tr><td> Rating: ".round((($mssum*0.05)/12),-2)."</td></tr>";
		}
	print "<tr><td align='center'> <a href='shop.php?pssnuser'>CHANGE PASSWORD</a>";
	print "</td></tr>";
}		

echo "</table></form>";
}
?>
<!-- start of table to for to do record  -->
<?php
echo' 
<form>
<table> 
<tr><th><b>YOUR TO DO LIST FOR TODAY</b></th></tr>';
// Check that the session variable 'ShopLogin' is set
if (isset($_SESSION['ShopLogin']) && $_SESSION['ShopLogin'] == true && $_SESSION['Level'] == '4') {
  // Sanitize user input
  $sutor = mysqli_real_escape_string($conn, $_SESSION['SAdmin']);
  // Display list of recurring tasks for the day
  $today = date('d');
  // Days events
  $todac = date('l');
  $query = "SELECT * FROM shoptasks WHERE (DueDate = '$today' AND Status = 'Pending') OR (DueDate = '$todac' AND Status = 'Pending') ORDER BY Sno DESC";
  $result = mysqli_query($conn, $query);
  if ($result) {
    while ($row = mysqli_fetch_array($result)) {
      echo "<tr><td colspan='4'><ul>" . $row[3] . "</ul></td></tr>";
    }
  } else {
    // If the query was unsuccessful, display an error message
    echo "Error executing query: " . mysqli_error($conn);
  }

  // Display list of tasks for the day
  $ty = date('d/m');
  $query = "SELECT * FROM shoptasks WHERE DueDate = '$ty' AND Status = 'Pending' AND Incharge = '$sutor' AND Activity != 'Birthday' ORDER BY Sno DESC";
  $result = mysqli_query($conn, $query);
  if ($result) {
    while ($row = mysqli_fetch_array($result)) {
      echo "<tr><td colspan='4'><a href=shop.php?done=" . $row[0] . ">" . $row[3] . "</a></td></tr>";
    }
  } else {
    // If the query was unsuccessful, display an error message
    echo "Error executing query: " . mysqli_error($conn);
  }

  // Display list of recurring tasks for the week
  $today = date('d/m');
  $query = "SELECT * FROM shoptasks WHERE DueDate > '$today' AND Status = 'Pending' ORDER BY Sno ASC LIMIT 3";
  $result = mysqli_query($conn, $query);
  if ($result) {
    echo "<tr><td  align='center' style='color: red;'>UPCOMING EVENTS</td></tr>";
    while ($row = mysqli_fetch_array($result)) {
      echo "<tr><td colspan='4'>" . $row[1] . " " . $row[3] . "</td></tr>";
    }
  } else {
    // If the query was unsuccessful, display an error message
    echo "Error executing query: " . mysqli_error($conn);
  }


 }

 else {
  // display tasks and events for other users
  // Sanitize user input
  $sutor = mysqli_real_escape_string($conn, $_SESSION['SAdmin']);
  // Display list of recurring tasks for the day
  $today = date('d');
  // Days events
  $todac = date('l');
  $query = "SELECT * FROM shoptasks WHERE ((DueDate = '$today' AND Status = 'Pending') OR (DueDate = '$todac' AND Status = 'Pending')) AND Incharge = '$sutor' ORDER BY Sno DESC";
  $result = mysqli_query($conn, $query);
  if ($result) {
	  if(mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
      echo "<tr ><td colspan='4'><ul>" . $row[3] . "</ul></td></tr>";
    }
	  }
	  else{
		 // Display list of tasks for the day
		 $ty = date('d/m');
		 $query = "SELECT * FROM shoptasks WHERE DueDate = '$ty' AND Status = 'Pending' AND Activity != 'Birthday' AND Incharge = '$sutor' ORDER BY Sno DESC";
		 $result = mysqli_query($conn, $query);
		 if ($result) {
			 if(mysqli_num_rows($result) > 0) {
				 while ($row = mysqli_fetch_array($result)) {
					 echo "<tr><td colspan='4' align='center'><a href=shop.php?tdone=" . $row[0] . ">" . $row[2] . "</a></td></tr>";
					 }
				}
			else {
				echo "<tr><td colspan='4' align='center'>Your Free Today</td></tr>";
				}
			}
		else {
			// If the query was unsuccessful, display an error message
			echo "Error executing query: " . mysqli_error($conn);
			}
		}
	}
	else {
    // If the query was unsuccessful, display an error message
    echo "Error executing query: " . mysqli_error($conn);
  }
//display list of tasks for the day
 }
 echo '</table></form>';
?>
<?php
if (isset($_GET["tdone"]))
{
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "tdone", FILTER_SANITIZE_STRING)); //Assigned Task ID
$query="select * from shoptasks where Sno ='$search'";
$result=mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
	print "<table cellpadding='5px' cellspacing='0px' align='center' border='0'>";
	print "<tr>";
	print "<td > <B>Due Date</B>: ".$row[1]."</td >";
	print "<tr>";
	print "<td colspan='4'> <B>Details</B>: ".$row[3]." <a href=shop.php?done=".$row[0]."> Task Completed</a></td>";
	print "</tr>";
	print "</table>";
		}

echo "";

}
?>






<?php


//view summary of sales
if (isset($_GET["vhst"]))
{
echo'
<form name="" method="post" action="?vhst">
<table style=" border:1px solid silver" cellpadding="5" cellspacing="0" align="center" border="0" >
<tr><td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">VIEW SUMMARY</td></tr>
<tr>
<td>Start Date: <input type="date" name="tsdv" size="20" required></td>
<td>End Date: <input type="date" name="tedv" size="20" required></td>

</tr>
<tr>
<td colspan="4" align="center"><input type="hidden" name="ttv" value="sttv">
<input type="submit" value="GET RECORDS"></td>
</tr>
</table>';
echo '</form>';

}
if (isset($_POST["ttv"])=="sttv")
{
echo'
<form name="" method="post" action="shop.php">
<table style=" border:1px solid silver" cellpadding="5" cellspacing="0" align="center" border="0" >
<tr>
<td colspan="4">';

$dfr=date('Y-m-d', strtotime($_POST['tsdv'])); //Start date
$fdr=date('Y-m-d', strtotime($_POST['tedv'])); //End date
//display total sales
$query="SELECT SUM(cost) AS mtotal FROM shopsales WHERE Date BETWEEN '$dfr' AND '$fdr' order by Date asc";
$mresult= mysqli_query($conn,$query);
$mrow = mysqli_fetch_assoc($mresult); 
$msum3 = $mrow['mtotal'];

$query="SELECT SUM(cost) AS smtotal FROM shopexpenses WHERE Date BETWEEN '$dfr' AND '$fdr' order by Date asc";
$mresult= mysqli_query($conn,$query);
$mrow = mysqli_fetch_assoc($mresult); 
$smsum3 = $mrow['smtotal'];

echo "<tr><td>From: ". $dfr. " to: ".$fdr." </td></tr>
<tr><td>Money Made:".number_format($msum3)." </td></tr>
<tr><td>Expenses:".number_format($smsum3)."</td></tr> 
<tr><td>Gross Profits:".number_format($msum3-$smsum3)."</td></tr>
";
//".number_format($wsum, 2, '.', '')." ";
$query="select Sno,date, Item,count(*) as Total,SUM(cost) AS ssson from shopsales WHERE Date BETWEEN '$dfr' AND '$fdr' group by Item ORDER BY Total DESC";
$result=mysqli_query($conn,$query);
if(mysqli_num_rows($result) > 0) {

	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td>".$row[2]." - ".number_format($row[4])."</td></tr>";	
	}

}
echo'<tr><td><a href="Bus.php">Export</a></td></tr>
</table></form>';
}
else{
	echo "";
}
?>

<?php
//add new activity
if (isset($_GET["loaction"]))
{
	echo '<form name="shop" method="post" action="shop.php">
<h1>TO DO LIST RECORD</h1></tr>
<input type="text" name="ticfeyc" size="20" placeholder="Name of Task" required>
<input type="text" name="ticflanom" size="30" placeholder="Just the day e.g 01,22 or 25/08" required>
<input type="text" name="ticffinom" size="20" placeholder="Details of what you intend to do" required>

<select name="ticflbnom" class="span12" required>
<option value="">Enter Status</option>
<option value="Pending">Pending</option>
<option value="Complete">Complete</option>
<option value="Approved">Approved</option>
</select>

<select name="ticfiot" class="span10" required>
<option value="">Incharge</option>';
$query="select * from shopusers where username!='Admin' and username!='Robin' order by id asc";
$result=mysqli_query($conn,$query);
while ($row = mysqli_fetch_array($result)) {
	echo '<option value="'.$row[1].'">'.$row[1].'</option>';
	}
	echo '</select></td>

<input type="hidden" name="todo" value="actoNT">
<input type="submit" value="ADD TASK">
</form>';
}
else {
	echo '';
}
?>

<?php
//Password change
if (isset($_GET["pssnuser"]))
{
echo '
<form name="newpc" method="post" action="shop.php">
<h1>CHANGE USER PASSWORD</h1>
<input type="password" name="oups" placeholder="Enter Current Password" size="20" required>
<input type="password" name="nupc" placeholder="Enter New Password" size="20">
<input type="hidden" name="pcsu" value="NPC">
<input type="submit" value="CHANGE PASSWORD">
</form>';
}

//New System
if (isset($_GET["losnuser"]))
{
echo '
<form name="new" method="post" action="shop.php">
<h1>ADD NEW USER RECORD</h1>
<input type="text" name="ticfffnom" size="20" placeholder= "Enter Username" required>
<input type="text" name="ticfblo" size="20" placeholder="Email Address">
<input type="text" name="ticffinom" size="20" placeholder="Enter Password" required>
<input type="text" name="ticflnnom" size="20" placeholder="Enter Contact Number" required>
<input type="text" name="ticflenom" size="20" placeholder="Employee Number" required>
<select name="ticflasom" class="span12" required>
<option value="">Account Status</option>
<option value="Active">Active</option>
<option value="Terminated">Terminate</option>
</select>
<select name="ticfletom" class="span12" required>
<option value="">Title</option>
<option value="1">Data Entrant / Trainee </option>
<option value="2">Client Service Executive</option>
</select> 
<input type="hidden" name="dno" value="NM">
<input type="submit" value="ADD RECORD">
<a href="shop.php?vlouser">USER MANAGEMENT</a>
</form>';
}

//user management
if (isset($_GET["vlouser"]))
{
//view list of posts
$query="select * from shopusers order by id asc";
$result=mysqli_query($conn, $query);
echo '<form name="shop" method="post" action="shop.php">
<table>
<tr><th>LIST OF USERS</th></tr>';
while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td> ",$row[6],"  ",$row[1],"
		&nbsp;<a href='shop.php?myprofile=",$row[1],"'>VIEW</a>
		&nbsp;<a href='shop.php?userpare=",$row[0],"'> RESET</a>
		&nbsp;<a href='shop.php?userparet=",$row[0],"'> TERMINATE</a>
		&nbsp;<a href='shop.php?userchud=",$row[0],"'> UPDATE</a></td></tr>";
		}

		print "<tr><td><a href='shop.php?vlouser'>USER MANAGEMENT</a></td></tr>";	
		print "</table></form>";
}

if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
//update profile
if (isset($_GET["userchud"]))
{
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "userchud", FILTER_SANITIZE_STRING));
$query="select * from shopusers where id ='$search'";
$result=mysqli_query($conn,$query);
echo '<form name="shop" method="post" action="shop.php">
<h1>USER PROFILE UPDATE</h1>';
while ($row = mysqli_fetch_array($result)) {
	print "User Name:<input type='text' name='userchudun' size='20' value='".$row[1]."' required>"; 
	print "Email:<input type='text' name='userchudue' size='20' value='".$row[2]."' required>";
	print "Client's Contact:<input type='text' name='userchudupn' size='20' value='".$row[5]."' required>";	
	print "CLOR:<input type='text' name='userchudlor' size='20' value='".$row[6]."' required>";
	print "Account Status:<input type='text' name='userchudac' size='20' value='".$row[7]."' required>";
	print "Employee ID:<input type='text' name='userchudei' size='20' value='".$row[9]."' required>";
	print "<input type='hidden' name='userchudsid' value='".$row[0]."'>";
	print "<input type='hidden' name='userparea' value='userchudsu'>";
	print "<input type='submit' value='UPDATE DETAILS'>";
	print "<a href='shop.php?vlouser'>USER MANAGEMENT</a>";	
	print "</form>";
		}
echo "";
}

else{
	echo "";
}
}

?>
<?php
if (isset($_GET["Newshoi"])){
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
echo '
<form name="shop" method="post" action="shop.php">
<h1>NEW SHOP ITEM RECORD</h1>
<input type="text" name="ticfnsi" size="20" placeholder="Item Name" required>
<input type="text" name="ticflnsqb" size="20" placeholder="Quantity Bought" required>
<input type="text" name="ticfnspfa" size="20" placeholder="Price for all" required>
<input type="text" name="ticflnsbp" size="20" placeholder="Break down of Item" required>
<input type="text" name="ticfnssp" size="20" placeholder="Selling Price" required>
<input type="text" name="ticflnsmt" size="20" placeholder="Monthly target" required>
<input type="hidden" name="shitodo" value="ShiNT">
<input type="submit" value="ADD ITEM">
<a href="shop.php?shsm">STOCK MANAGEMENT</a>
&nbsp; &nbsp;<a href="shop.php?loaction">NEW ACTIVITY</a>
&nbsp; &nbsp;<a href="shop.php?discot">ADD DISCOUNT TOKEN</a>
&nbsp; &nbsp;<a href="shop.php?losnuser">NEW SYSTEM USER</a>

</form>
';
 }
 }
 if (isset($_GET["debts"]))
{
$searchd = mysqli_real_escape_string($conn, filter_input(INPUT_GET, "debts", FILTER_SANITIZE_STRING)); // Client's SysID
// Search shopdebtors table
$query = "SELECT * FROM shopdebtors WHERE Sno = '$searchd'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<form name='debts' method='post' action='shop.php'>";
    echo '<table style="border:1 px solid silver; padding:5px; margin: 0 auto;">';
	echo '<tr><td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">UPDATE DEBTORS RECORD</td></tr>';
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr><td><b>Debtor:</b><input type='text' name='dbtnm' value='".$row[5]."' readonly></td></tr>";
        echo "<tr><td><b>Current Balance:</b><input type='text' name='blpdt' value='".$row[6]."' required></td></tr>";
        echo "<tr><td><b>Amount Paid:</b><input type='text' name='amtpd' required></td></tr>";
        echo "<input type='hidden' name='pymdtid' value='".$row[0]."'>";
        echo "<tr><td colspan='4' align='center'><input type='hidden' name='pymdt' value='Nedt'>";
        echo "<tr><td colspan='4' align='center'><input type='submit' value='Update'></td></tr>";
    }
	    echo "</table></form>";
}
}
?>






<?php
if (isset($_GET["shsm"]))
{
//view list of shop item
$query="select * from shopitems order by Item ASC";
$result=mysqli_query($conn, $query);
echo '<form name="shop" method="post" action="shop.php">';
echo '<table>';
echo '<tr><th colspan="2">STOCK MANAGEMENT</th></tr>
  <tr>
    <td>Item</td>
    <td>Action</td>
  </tr>';
while ($row = mysqli_fetch_array($result)) {
		echo "<tr>
		<td>",$row[2],"</td>
		<td>&nbsp;<a href='shop.php?shsmsi=",$row[0],"'>VIEW</a>
		&nbsp;<a href='shop.php?shsmci=",$row[0],"'>UPDATE</a>
		</td></tr>";
		}
		print "<tr><td colspan='2'><a href='shop.php?Newshoi'>NEW SHOP ITEM RECORD</a>	</td></tr>
		</table></form>";

}

if (isset($_GET["shsmsi"]))
{
//view product profile
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "shsmsi", FILTER_SANITIZE_STRING)); // Client's SysID
$query="select * from shopitems where Sno ='$search'";
$result=mysqli_query($conn, $query);
echo '<form>
<table>
<tr><th colspan="4">PRODUCT PROFILE</th></tr>';
while ($row = mysqli_fetch_array($result)) {
	print "<tr><td colspan='4' align='center'> Added on: ".$row[1]."</td></tr>";	
	print "<tr align=left><td colspan='4'> Product Name: ".$row[2]."</td></tr>";
	print "<tr align=left><td colspan='4'> Bulk Purchase Price: ".$row[3]."</td></tr>";
	print "<tr align=left><td colspan='4'> Bulk Quantity Bought: ".$row[4]."</tr>";
	print "<tr align=left><td colspan='4'> Current Selling Price: ".$row[6]."</tr>";
	print "<tr align=left><td colspan='4'> Current Buying Price: ".round(($row[3]/$row[4]),-1)."</tr>";	
	print "<tr align=left><td colspan='4'> Status: ".$row[8]."</tr>";
	print "<tr align=left><td colspan='4'> Notes: ".$row[5]."</tr>";
	echo "<tr align=left>
		<td colspan='1'>&nbsp;<a href='shop.php?shsm'>STOCK MANAGEMENT</a></td>
		<td colspan='1'>&nbsp;<a href='shop.php?shsmci=",$row[0],"'>UPDATE</a></td>
		</tr>";	
		}
echo '</table></form>';
}

//update profile
if (isset($_GET["shsmci"]))
{
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "shsmci", FILTER_SANITIZE_STRING)); // Client's SysID
$query="select * from shopitems where Sno ='$search'";
$result=mysqli_query($conn,$query);
echo '<form name="shop" method="post" action="shop.php">
<h1>PRODUCT PROFILE UPDATE</h1>';
while ($row = mysqli_fetch_array($result)) {
	print "<input type='text' name='spbe' size='20' value='".$row[2]."' required>"; 
	print "<input type='text' name='spbpp' size='20' value='".$row[3]."' required>";
	print "<input type='text' name='spbqp' size='20' value='".$row[4]."' required>";
	print "<input type='text' name='spcs' size='20' value='".$row[8]."' required>";
	echo "<textarea name='spcn' rows='1' cols='70'>".stripslashes($row[5]).
	" What did it cost, how many are currently in stock
	"."</textarea>";
	print "<input type='hidden' name='spbeid' value='".$row[0]."'>";
	print "<input type='hidden' name='shspc' value='shspce'>";
	print "<input type='submit' value='UPDATE DETAILS'>";
	print "<a href='shop.php?shsm'>STOCK MANAGEMENT</a>";	

		}
	print "</form>";
}


?>


<?php
//Debt
if (isset($_GET["dextr"]))
{
echo'
<form name="" method="post" action="?dextr">
<h1>DEBTOR EXTRACTOR</h1>
<input type="text" name="xtrd" size="30" placeholder="Name of debt"required>
<input type="hidden" name="xrdn" value="xrdnv">
<input type="submit" value="GET RECORDS"></td>
<a href="shop.php?adebts">ADD DEBTOR</a>
<a href="shop.php?badebts">BAD DEBTOR</a>
<a href="shop.php?lodebts">VIEW DEBTORS</a>
</form>';
}
if (isset($_POST["xrdn"])=="xrdnv")
{
echo'
<form name="" method="post" action="?dextr">
<table>
<tr>
<th colspan="4" align="center">Uncleared Transactions Balance</th>
</tr>';

$xtre=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "xtrd", FILTER_SANITIZE_STRING)); // Transaction Remarks
//display debts
$query="select * from shopdebtors where Remark like '%$xtre%' and Balance > '0' ORDER BY Sno DESC";
$result=mysqli_query($conn,$query);
if(mysqli_num_rows($result) > 0) {	
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td colspan='4'>";
		echo $row[1]." ".$row[5]." 
		<b style='color: red;'>Balance: <a href='shop.php?debts=".$row[0]."'>",$row[6],"</a></b></td></tr>";
	}
}
	$query="SELECT Remark,SUM(Balance) AS totaldebt FROM shopdebtors where Remark like '%$xtre%' and Balance > '0'";
	$result= mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result); 
	$sbum = $row['totaldebt'];
	if($sbum >0)
		{
	echo "<tr><td colspan='4' align='center'> Current Total Debt: <b style='color: red;'>".$sbum.  "</b></td></tr>";
		}
		else{
			echo "<tr><td colspan='4' align='center'>No records found </td></tr>";
			}

echo '</table></form>';
}
else{
	echo "";
}
?>

<?php
//View Debts
if (isset($_GET["lodebts"]))
	{	
	
	echo "<form><table>";
	echo '<tr><th colspan="4" align="center"">PEOPLE WITH DEBTS:';
	$query="SELECT SUM(Balance) AS totalcollection FROM shopdebtors where Balance > 0";
	$result= mysqli_query($conn,$query);
	$row = mysqli_fetch_assoc($result); 
	$sum = $row['totalcollection'];
	echo $sum;
	echo '</th></tr>';
echo "<tr>
		<td> Date</td>
		<td>Remark</td>
		<td>Total Debt</td>
	</tr>";
	$qury="select Sno, Date,Remark,Balance from shopdebtors where Balance >0 order by date desc";
	$qury="SELECT Date, Sno, Balance,Remark, LEFT(Remark, 3) as RemarkGroup, SUM(Balance) as TotalDebt FROM shopdebtors where Balance > 0 GROUP BY LEFT(Remark, 3) ORDER BY TotalDebt DESC";
	$result=mysqli_query($conn,$qury);
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr>
		<td> ",$row['Date'],"</td>
		<td>",$row['Remark'],"</td>
		<td>",$row['TotalDebt'],"</td><tr>";
		}


echo '</table></form>';

}
else
{
	echo "";
}

//Add Debtors
if (isset($_GET["adebts"]))
	{
echo '		
<form name="shop" method="post" action="shop.php">
<h1>ADD DEBTOR RECORD</h1>
<select name="ticfeyc" class="span10" required>
<option value="">Select Item</option>';
$query="select * from shopitems where Status='Active' order by item asc";
$result=mysqli_query($conn,$query);
while ($row = mysqli_fetch_array($result)) {
	echo '<option value="'.$row[2].'">'.$row[2]." - ".$row[6].'</option>';
	}
echo "";
echo '
</select>
<input type="number" name="ticflaqty" size="15" placeholder="Quantity Sold" required>
<input type="text" name="ticffinom" size="40" placeholder="Remarks"required>
<input type="text" name="ticflanom" size="10" placeholder="Unpaid Amount" required>
<input type="hidden" name="shdet" value="NSDT">
<input type="submit" value="ADD DEBT"></td>
</form>';


}
if (isset($_GET["badebts"]))
{
echo '
<form name="shop" method="post" action="shop.php">
<h1>PEOPLE WITH DEBTS:</h1>
<table>';
	$query="select * from shopsales where Remark like '%written off%'  order by Sno desc";
	$result=mysqli_query($conn,$query);
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr>
		<td> ",$row[1],"</td>
		<td>",$row[5],"</td>
		<td><a href='shop.php?debts=".$row[0]."'>",$row[6],"</a></b></td></tr>";
		}


echo '</table></form>';
}
else
{
	echo "";
}
?>
<?php
//search sales
if (isset($_GET["tsearch"]))
{
echo'
<form name="" method="post" action="?tsearch">
<table style=" border:1px solid silver" cellpadding="5" cellspacing="0" align="center" border="0" >
<tr><td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">SEARCH SALES</td></tr>
<tr><td>Transaction: <input type="text" name="stsv" size="20" required></td>
</tr>
<tr>
<td colspan="4" align="center"><input type="hidden" name="trase" value="trasev">
<input type="submit" value="GET RECORDS"></td>
</tr>
</table>
</form>';
}
if (isset($_POST["trase"])=="trasev")
{
echo'
<form name="" method="post" action="shop.php">
<table>
<tr><th>TRANSACTION</th></tr>';

$trasre=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "stsv", FILTER_SANITIZE_STRING)); // Transaction Remarks
//display total sales
$query="select * from shopsales where Remark like '%$trasre%' or item like '%$trasre%' ORDER BY Sno DESC";
$result=mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0) {

	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td><b>".$row[2]."</b> ".$row[5]." (Date of Transaction: ".$row[1]." Paid: ".$row[4].") "."</td></tr>";	
	}
}
echo'
</table></form>';
}
else{
	echo "";
}
?>

<?php 
if (isset($_GET["Nsexpense"])){
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] <= 4) {
echo '
<form name="shop" method="post" action="shop.php">
<table style=" border:1px solid silver" cellpadding="5px" cellspacing="0px"align="center" border="0" >
<tr>
<td colspan="3" style="background:"white"; color:#FFFFFF; align:centre">
</td></tr>
<tr>
<td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">NEW EXPENSE RECORD</td>
</tr>
<tr>
<td><select name="ticfnen" class="span10" required>
<option value="">Select Item</option>
<option value="Airtime">Airtime</option>
<option value="Boda">Boda</option>
<option value="Drinks">Drinks / Food</option>
<option value="Electricity">Electricity</option>
<option value="Mobile Money">Mobile Money</option>
<option value="Others">Others</option>
<option value="Packaging">Packaging</option>
<option value="Personal">Personal</option>
<option value="Repairs">Repairs</option>
<option value="Stock">Business Stock</option>

</select></td>
<td><input type="number" name="ticfeq" Placeholder="Expense Quantity" size="20" required></td>
</tr>

<tr>
<td><input type="number" name="ticfea" Placeholder="Expense Amount" size="20" required></td>
<td><input type="text" name="ticfer" Placeholder="Remarks" size="20" required></td>
</tr>

<tr>
<td colspan="4" align="center"><input type="hidden" name="shopcex" value="ShopExp">
<input type="submit" value="ADD EXPENSE"></td>
</tr>
<tr><td colspan="4" align="center"><a href="shop.php?shem">EXPENSE MANAGEMENT</a></td></tr>
</table>
</form>';
}
}
	
?>

<?php
$today =  date('Y-m-d');
//display total daily sales
$query="SELECT SUM(cost) AS totalcollection FROM shopsales where Date='$today'";
$result= mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result); 
$sum = $row['totalcollection'];

//display total daily expenses
$query="SELECT SUM(cost) AS totalexpenses FROM shopexpenses where Date='$today'";
$result= mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result); 
$esump = $row['totalexpenses'];

//display total weekly expenses
$query="SELECT SUM(cost) AS totalexpenses FROM shopexpenses WHERE WEEKOFYEAR(date)=WEEKOFYEAR(NOW()) AND YEAR(date) = YEAR(NOW())";
$result= mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result); 
$wsump = $row['totalexpenses'];

//display total weekly sales
$query = "SELECT SUM(cost) AS totalcollection FROM shopsales WHERE WEEKOFYEAR(date) = WEEKOFYEAR(NOW()) AND YEAR(date) = YEAR(NOW())";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result); 
$wsum = $row['totalcollection'];

//display total monthly sales
$dfr= date('Y-m-01');
$fdr=date('Y-m-t');
$query="SELECT SUM(cost) AS totalcollection FROM shopsales WHERE Date BETWEEN '$dfr' AND '$fdr' order by Date asc";
$result= mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result); 
$msum = $row['totalcollection'];

//display total monthly Expense
$dfr= date('Y-m-01');
$fdr=date('Y-m-t');
$query="SELECT SUM(cost) AS totalexpenses FROM shopexpenses WHERE Date BETWEEN '$dfr' AND '$fdr' order by Date asc";
$result= mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result); 
$msump = $row['totalexpenses'];

?>
<?php
if (isset($_GET["shem"]))
{
$today = date('Y-m-d');
$query="select * from shopexpenses where Date='$today' ORDER BY Sno desc";
$result=mysqli_query($conn,$query);
echo '<form name="shop" method="post" action="shop.php">';
echo '<table style=" border:1px solid silver" cellpadding="5px" cellspacing="0px" align="center" border="0" >';

echo '<tr>';
echo '<td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">EXPENSE MANAGEMENT</td>';
echo '</tr>';echo '<tr>';
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
while ($row = mysqli_fetch_array($result)) {
		echo '<tr align=left><td colspan="2">',$row[2],'</td>
		<td colspan="1">&nbsp;<a href="shop.php?shemsi=',$row[0],'#NewExp">VIEW</a></td>
		<td colspan="1">&nbsp;<a href="shop.php?shemci=',$row[0],'#NewExp">UPDATE</a></td>
		</tr>';
		}
		
		echo '<td colspan="4" align="center"><B>TODAY:</B> '.$esump.' <B>THIS WEEK:</B> '.$wsump.' <B>THIS MONTH:</B> '.$msump.'</td></tr>';
		echo '<td colspan="4" align="center">
		<input type="text" name="estsv" size="20" placeholder="Expense Name" required>
		<input type="hidden" name="etrase" value="etrasev">
		</td></tr>';
}
else{
while ($row = mysqli_fetch_array($result)) {
	echo '<tr align=left><td colspan="2">',$row[2],'</td>
		<td colspan="1">&nbsp;<a href="shop.php?shemsi=',$row[0],'#NewExp">VIEW</a></td>
		</tr>';
		}
echo '<td colspan="4" align="center"><B>TODAY:</B> '.$esump.'</td></tr>';		
}
		echo '</tr>';	
		echo '</table></form>';
echo '';

}

if (isset($_POST["etrase"])=="etrasev")
{
echo'
<form name="" method="post" action="shop.php">
<table style=" border:1px solid silver" cellpadding="5" cellspacing="0" align="center" border="0" >
';
$etrasre=mysqli_real_escape_string($conn, filter_input(INPUT_POST, "estsv", FILTER_SANITIZE_STRING)); // Transaction Remarks
$equery="SELECT Remark,SUM(cost) AS totalexpenses FROM shopexpenses where Item like '%$etrasre%' or Remark like '%$etrasre%'";
$eresult= mysqli_query($conn,$equery);
$row = mysqli_fetch_assoc($eresult); 
$esbum = $row['totalexpenses'];
//display total expense
echo'<tr>
<td colspan="4">';
echo "<b>Total Expense: </b>".$esbum."</td></tr>";
$query="select * from shopexpenses where Item like '%$etrasre%' or Remark like '%$etrasre%' ORDER BY Sno DESC";
$result=mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td colspan='4'><b>".$row[1]."</b> ".$row[5]." ".$row[4]."</td>
</tr>";	
	}
}
echo'

<tr align=left>
		<td colspan="1">&nbsp;<a href="shop.php?shem">EXPENSE MANAGEMENT</a></td>
		</tr>
		
</table></form>';
}

if (isset($_GET["shemsi"]))
{
//view expense profile
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "shemsi", FILTER_SANITIZE_STRING)); 
$query="select * from shopexpenses where Sno ='$search'";
$result=mysqli_query($conn,$query);
echo '<form name="shop" method="post" action="shop.php">';
echo '<table style=" border:1px solid silver" cellpadding="5px" cellspacing="0px" align="center" border="0" >';
echo '<tr>';
echo '<td colspan="3" style="background:"white"; color:#FFFFFF; align:centre"></td>';
echo '</tr>';
echo '<tr>';
echo '<td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">EXPENSE DETAILS</td>';
echo '</tr>';echo '<tr>';
while ($row = mysqli_fetch_array($result)) {
	print "<tr><td colspan='4' align='center'> Added on: ".$row[1]."</td></tr>";	
	print "<tr align=left><td colspan='4'> Expense Name: ".$row[2]."</td></tr>";
	print "<tr align=left><td colspan='4'> Quantity: ".$row[3]."</td></tr>";
	print "<tr align=left><td colspan='4'> Cost: ".$row[4]."</tr>";
	print "<tr align=left><td colspan='4'> Remarks: ".$row[5]."</tr>";

		}

	echo "<tr align=left>
		<td colspan='1'>&nbsp;<a href='shop.php?shem'>EXPENSE MANAGEMENT</a></td>
		</tr>";
echo '</table></form>';
}



//update Expense details
if (isset($_GET["shemci"]))
{
//$search=mysqli_real_escape_string($_GET["shemci"]);
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "shemci", FILTER_SANITIZE_STRING)); 
$query="select * from shopexpenses where Sno ='$search'";
$result=mysqli_query($conn, $query);
echo '<form name="shop" method="post" action="shop.php">';
echo '<table style=" border:1px solid silver" cellpadding="5px" cellspacing="0px"align="center" border="0" >';
echo '<tr>';
echo '<td colspan="4" align="center" style="background:#90908e; color:#FFFFFF; ">EXPENSE DETAILS UPDATE</td>';
echo '</tr>';echo '<tr>';
while ($row = mysqli_fetch_array($result)) {
	print "<tr><td colspan='4' ><input type='text' name='sebe' size='20' value='".$row[2]."' placeholder='Item Name' required></td></tr>"; 
	print "<tr><td colspan='4' ><input type='text' name='sebpp' size='20' value='".$row[3]."' placeholder='Expense Quantity' required></td></tr>";
	print "<tr><td colspan='4' ><input type='text' name='sebqp' size='20' value='".$row[4]."' placeholder='Expense Amount' required></td>";
	print "<tr><td colspan='4' ><input type='text' name='sepcs' size='20' value='".$row[5]."' placeholder='Remarks'required></td></tr>";
	print "<td colspan='4' align='center'><input type='hidden' name='sebeid' value='".$row[0]."'></td></tr>";
	print "<tr><td colspan='4' align='center'><input type='hidden' name='shepc' value='shsece'>";
	print "<input type='submit' value='UPDATE DETAILS'></td></tr>";
	print "<td colspan='4' align='center'><a href='shop.php?shem'>EXPENSE MANAGEMENT</a></td></tr>";	
	
	print "</table></form>";
		}
echo "";
}

//update transaction details
if (isset($_GET["utr"]))
{
  $t = date('Y');
  $td = date('m');
  $number = cal_days_in_month(CAL_GREGORIAN, $td, $t);
  $current_date =  date('Y-m-d');
  $stmt = mysqli_prepare($conn, "SELECT * FROM shopsales WHERE Date=? ORDER BY Sno desc");
  mysqli_stmt_bind_param($stmt, "s", $current_date);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if(!$result) {
      die("Query failed: " . mysqli_error($conn));
  }
 echo "<form><table>";
  while ($row = mysqli_fetch_array($result)) {
      echo "<tr>
        <td>{$row[1]}</td>
        <td colspan='1'><a href='shop.php?sutr={$row[0]}'>{$row[2]}</a></td>		
        <td>{$row[4]}</td>
      </tr>";
  }	
 echo "</table></form>";
  
}

//update tranaction details
if (isset($_GET["sutr"]))
{
$search=mysqli_real_escape_string($conn, filter_input(INPUT_GET, "sutr", FILTER_SANITIZE_STRING)); 
$query="select * from shopsales where Sno ='$search'";
$result=mysqli_query($conn, $query);
echo '<form name="shop" method="post" action="shop.php">';
echo '<table>';
echo '<tr>';
echo '<th>TRANSACTION UPDATE DETAILS</th>';
echo '</tr>';
while ($row = mysqli_fetch_array($result)) {
  echo '<tr><td><select name="nsutr" class="span10" required>
    <option value="">Item Name</option>';
    $iquery = "SELECT * FROM shopitems WHERE Status='Active' ORDER BY item ASC";
    $iresult = mysqli_query($conn, $iquery);
    while ($irow = mysqli_fetch_array($iresult)) {
        echo '<option value="' . $irow[2] . '">' . $irow[2] . " - " . $irow[6] . '</option>';
    }
  echo '</select></td></tr>';	
echo "<tr><td>Quantity:<input type='number' name='qsutr' size='20' value='".$row[3]."' required></td></tr>";
echo "<tr><td> Amount:<input type='number' name='asutr' size='20' value='".$row[4]."' required></td></tr>";	
echo "<tr><td>Details:<input type='text' name='dsutr' size='20' value='".$row[5]."' required></td></tr>";
echo "<tr><td>Reason for updating:<input type='text' name='rsutr' size='20' value='' required></td></tr>";
echo "<tr><td colspan='4' align='center'><input type='hidden' name='tbsutr' value='".$row[0]."'>
	<input type='hidden' name='assutr' value='arssutr'>
	<input type='submit' value='UPDATE DETAILS'></td></tr>";
	echo "</table></form>";
		}
echo "";
}

?>


<?php
echo '<form>
<table>';	
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
	echo "<tr><th th colspan='3'><a href='shop.php?salestoday'> SALES TRANSACTION </a> </th></tr>";
	}
elseif (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '2') {
	echo "<tr><th colspan='3'><a href='shop.php?salestoday'>SALES TRANSACTION:</a> </th></tr>";
	}

else {
echo "<tr><th>DAILY SALES:</th></tr>";
}
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '1') {
	echo '<tr>
<td>Date</td>
<td>Item</td>
</tr>';
$t = date('Y');
$td = date('m');
$number = cal_days_in_month(CAL_GREGORIAN, $td, $t);
$current_date =  date('Y-m-d');
$stmt = mysqli_prepare($conn, "SELECT * FROM shopsales WHERE Date=? ORDER BY Sno desc LIMIT 5");
mysqli_stmt_bind_param($stmt, "s", $current_date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(!$result) {
die("Query failed: " . mysqli_error($conn));
}
while ($row = mysqli_fetch_array($result)) {
echo "<tr>
<td>{$row[1]}</td>
<td colspan='1'>{$row[2]}</td>		

</tr>";
}
}

	else {
	echo '	
	<tr>
	<td>Date</td>
	<td>Item</td>';
	//<td>Amount</td>
	echo '</tr>';
  $t = date('Y');
  $td = date('m');
  $number = cal_days_in_month(CAL_GREGORIAN, $td, $t);
  $current_date =  date('Y-m-d');
  $stmt = mysqli_prepare($conn, "SELECT * FROM shopsales WHERE Date=? ORDER BY Sno desc LIMIT 5");
  mysqli_stmt_bind_param($stmt, "s", $current_date);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if(!$result) {
      die("Query failed: " . mysqli_error($conn));
  }
  while ($row = mysqli_fetch_array($result)) {
      echo "<tr>
        <td>{$row[1]}</td>
        <td colspan='1'><a href='shop.php?details={$row[0]}'>{$row[2]}</a></td>	";	

       echo "</tr>";
	}
	}
	


echo '
<tr>
<td colspan="3">';
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
$t = date('Y');
$td = date('m');

//number of days in current month
$number = cal_days_in_month(CAL_GREGORIAN, $td, $t); // 31
$rinsf=round($msum/$number,-3);
// total monthly collections divided by number of days in month

echo "D.Average: ",round($msum/$number,-3);
echo "&nbsp; W.Average: ",round($msum/4,-3);
echo "&nbsp;";
echo "T.Check: ", ROUND(((($msum/2500000)*100)),0),"%"; // increased from 1500000
}
echo '</td>
</table>
</form>';
?>

<?php
if (isset($_GET["details"]))
{
//transaction details
$acti = filter_input(INPUT_GET, "details", FILTER_SANITIZE_STRING);
$query = "SELECT Sno,Date,Item,QtySold ,cost,Remark,Handler FROM shopsales WHERE Sno = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $acti);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
echo "<form> <table>";
while ($row = mysqli_fetch_array($result)) {
    print "<tr><td> Date: ".$row['Date'];
    print "</tr>";
    print "<tr><td> Transaction: ".$row['Item'];
    print "</tr>";
    print "<tr><td style='color: red;'> Amount: ".$row['cost']."&nbsp;&nbsp;&nbsp;&nbsp; Quantity: ".$row['QtySold']."&nbsp;&nbsp;&nbsp;&nbsp; By: ".$row['Handler'];
    print "</tr>";
    print "<tr><td> Details: ".ucwords(strtolower($row['Remark']));
    print "</tr>";
}
echo "</table></form>";
}	
?>	

<?php
//daily sales view
if (isset($_GET["salestoday"]))
{
	if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
echo '<form>
<table>';	
	echo "<tr><th><a href='shop.php?salestoday'>DAYS SALES:</a> " . number_format($sum, 2, '.', '') . "</th>";
	echo "<th><a href='shop.php?salesweek'>WEEK:</a> " . number_format($wsum, 2, '.', '') . "</th>";
	echo "<th><a href='shop.php?salesmonth'>MONTHS:</a> " . number_format($msum, 2, '.', '') . "</th></tr>";
	echo "<tr><td colspan='5'>Sales: ".number_format($sum, 2, '.', '')."</td></tr>";
    $queery = "SELECT 
        SUM(shopsales.cost - ((shopitems.BulkPurchasePrice / shopitems.BulkPurchaseQty)*shopsales.QtySold)) AS TodayProfit
    FROM 
        shopsales
    JOIN 
        shopitems ON shopsales.Item = shopitems.Item
    WHERE 
        shopsales.Date = CURDATE()";
    if ($reesult = mysqli_query($conn, $queery)) {
        $row = mysqli_fetch_array($reesult);
            echo '<tr><td colspan="3" style="color:red; font-size:12px; text-align:center;">
            Profit: '.number_format($row['TodayProfit'], 2).'
            CASH: '.number_format($sum-$esump).'
   </td></tr>';	
	}
	
	
	
	
echo '</table></form>';
          $query = "SELECT 
    SUM(shopsales.cost - ((shopitems.BulkPurchasePrice / shopitems.BulkPurchaseQty)*shopsales.QtySold)) AS TodayProfit
FROM 
    shopsales
JOIN 
    shopitems ON shopsales.Item = shopitems.Item
WHERE 
    shopsales.Date = CURDATE()";
if ($result = mysqli_query($conn, $query)) {
    $row = mysqli_fetch_array($result);
		echo "<form><table>";
	print "<tr><td colspan='5'>Today's profit is: ".number_format($row['TodayProfit'], 2)."</td></tr>";
	$_SESSION['TPP'] = $row['TodayProfit'];

} else {
    echo "Error: " . mysqli_error($conn);
}

$today = date('Y-m-d');
$query="select * from shopsales where Date ='$today' order by Sno DESC ";
$result=mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
	print "<tr><td colspan='5'> Transaction: ".$row[2]." - ".$row[5]."</td></tr>";
	print "<tr><td style='color: red;'> Amount: ".$row[4]."&nbsp;&nbsp;&nbsp;&nbsp;  Handler: ".$row[6]."</td></tr>";
	}
echo "";
		}
		else{
			print "<form><table><tr><td colspan='5'>Sales: ".number_format($sum, 2, '.', '')."</td></tr></table></form>";
		}
		
echo"</tr>";
echo "</table></form>";	
}	
?>



<?php
//weekly sales view
if (isset($_GET["salesweek"]))
{
$query="SELECT shopsales.Item, SUM(cost) AS TotalRevenue, SUM(QtySold) AS TotalQty, 
    SUM(shopsales.cost - ((shopitems.BulkPurchasePrice / shopitems.BulkPurchaseQty)*shopsales.QtySold)) AS WeekProfit
FROM 
    shopsales
JOIN 
    shopitems ON shopsales.Item = shopitems.Item
WHERE
	shopsales.Date BETWEEN DATE_SUB(NOW(), INTERVAL DAYOFWEEK(NOW()) DAY) AND NOW()";

    $stmt = mysqli_prepare($conn, $query);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
			echo "<form><table>";
            while ($row = mysqli_fetch_array($result)) {
				echo "<tr><td colspan='5'>Weeks's profit is: ".number_format($row['WeekProfit'], 2)."</td></tr>";
            }
            
		} else {
            echo "No data available for this week";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);


//top sales
$query = "SELECT shopsales.Item, SUM(cost) AS TotalRevenue, SUM(QtySold) AS TotalSold FROM shopsales 
WHERE WEEKOFYEAR(date) = WEEKOFYEAR(NOW())AND YEAR(date) = YEAR(NOW()) GROUP BY Item ORDER BY TotalSold DESC LIMIT 3";
$stmt = mysqli_prepare($conn, $query);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr><td><i>Trending Item:</i> <b>" . $row['Item'] . "</b> Unit Sold: " . $row['TotalSold'] . " Total Revenue: UGX:" . $row['TotalRevenue']. "</td></tr>";
            }

        } else {
            echo "No data available for this month";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);


$query="select * from shopsales WHERE WEEKOFYEAR(date)=WEEKOFYEAR(NOW()) AND YEAR(date) = YEAR(NOW())";

    $stmt = mysqli_prepare($conn, $query);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {

			while ($row = mysqli_fetch_array($result)) {
					echo "<tr><td> Transaction: ".$row['Item']."</td></tr>";
	echo "<tr><td style='color: red;'> Date: ".$row['Date']." Amount: ".$row['cost']."&nbsp;&nbsp;  Handler: ".$row['Handler']."</td></tr>";

				
                echo "</tr>";
            }
            echo "</table></form>";
        } else {
            echo "No data available for this month";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

?>

<?php
if (isset($_GET["salesmonth"])) {

$dfr = date('Y-m-01', strtotime('first day of this month'));
$fdr = date('Y-m-t', strtotime('last day of this month'));

$query = "SELECT shopsales.Item, SUM(cost) AS TotalRevenue, SUM(QtySold) AS TotalQty,
SUM(shopsales.cost - ((shopitems.BulkPurchasePrice / shopitems.BulkPurchaseQty)*shopsales.QtySold)) AS MonthProfit
 FROM 
 shopsales
JOIN 
    shopitems ON shopsales.Item = shopitems.Item
WHERE
shopsales.Date BETWEEN ? AND ? ";
    $stmt = mysqli_prepare($conn, $query);
	
    mysqli_stmt_bind_param($stmt, "ss", $dfr, $fdr);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
			            echo "<form><table>";
            while ($row = mysqli_fetch_array($result)) {
               echo "<tr><td colspan='5'>Months's profit is: ".number_format($row['MonthProfit'], 2)."</td></tr>";
			   }
        } else {
            echo "No data available for this month";
			} 
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
	$dfr = date('Y-m-01', strtotime('first day of this month'));
    $fdr = date('Y-m-t', strtotime('last day of this month'));

    $query = "SELECT Item, SUM(cost) AS TotalRevenue, SUM(QtySold) AS TotalQty FROM shopsales 
    WHERE Date BETWEEN ? AND ? GROUP BY Item ORDER BY TotalQty DESC LIMIT 3";

    $stmt = mysqli_prepare($conn, $query);
	
    mysqli_stmt_bind_param($stmt, "ss", $dfr, $fdr);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
		          
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr><td><i>Trending Item:</i> <b>" . $row['Item'] . "</b> Unit Sold: " . $row['TotalQty'] . " Total Revenue: $" . $row['TotalRevenue']. "</td></tr>";
            }

        } else {
            echo "No data available for this month";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);

    $query = "SELECT * FROM shopsales WHERE Date BETWEEN ? AND ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $dfr, $fdr);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_array($result)) {
					echo "<tr><td> Transaction: ".$row['Item']."</td></tr>";
	echo "<tr><td style='color: red;'> Date: ".$row['Date']." Amount: ".$row['cost']."&nbsp;&nbsp;  Balance: ".$row['Handler'];

				
                echo "</tr>";
            }
            echo "</table></form>";
        } else {
            echo "No data available for this month";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>
<?php
if (isset($_GET["spaos"])) {
if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == 4) {
// SQL query to calculate the profit for each sale
$sql = "SELECT 
  ss.Item, 
  SUM(ss.QtySold) AS `QtySold`, 
  SUM(ss.cost) AS `costgot`,
  (si.BulkPurchasePrice / si.BulkPurchaseQty) AS `Buying Cost`, 
  SUM(ss.QtySold * (si.Selling - (si.BulkPurchasePrice / si.BulkPurchaseQty))) AS `Profit`
FROM 
  shopsales ss 
  JOIN shopitems si ON ss.Item = si.Item
WHERE 
  ss.Date BETWEEN '2023-03-15' AND '2023-04-14'
GROUP BY 
  ss.Item";
  
// execute the SQL query
$result = mysqli_query($conn, $sql);

// check if query execution was successful
if (!$result) {
  die("Query failed: " . mysqli_error($conn));
}

// display the results in an HTML table format


	  
echo "<form><table border='1'>
        <tr>
          <th>Item</th>
          <th>QtySold</th>
          <th>Buying Cost</th>
          <th>Profit</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>
          <td>" . $row['Item'] . "</td>
          <td>" . $row['QtySold'] . "</td>
          <td>" . number_format($row['Buying Cost']) . "</td>
          <td>" . number_format($row['Profit']) . "</td>
        </tr>";
}
echo "</table></form>";
  }
}
?>
<form name="sales" method="post" action="shop.php" autocomplete="off">
  <h1>ADD SALES RECORD</h1>
  
  <select name="ticfeyc" class="span10" required>
    <option value="">Select Item</option>
    <?php
    $query = "SELECT * FROM shopitems WHERE Status='Active' ORDER BY item ASC";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        echo '<option value="' . $row[2] . '">' . $row[2] . " - " . $row[6] . '</option>';
    }
    ?>
  </select>
  
  <input type="number" name="ticflaqty" size="15" placeholder="Quantity Sold" required>

  <input type="text" name="ticffinom" size="45" placeholder="Remarks" required>

  <input type="number" name="ticflanom" size="15" placeholder="Amount Paid" required>
  <input type="hidden" name="store" value="NT">
  <input type="submit" value="ADD RECORD">
    <?php 
  if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '2') {
    echo "
  &nbsp;&nbsp;&nbsp;<a href='shop.php?tsearch'>SEARCH</a>
  &nbsp;&nbsp;&nbsp;<a href='shop.php?utr'>UPDATE TRANSACTION</a>
  &nbsp;&nbsp;&nbsp;<a href='shop.php?dextr'>EXTRACT DEBTOR</a>
    &nbsp;&nbsp;&nbsp;<a href='shop.php?Nsexpense'>EXPENSE</a>
	&nbsp; &nbsp;<a href='shop.php?chat'>CHAT</a>";
  }
  if (isset($_SESSION['ShopLogin']) && $_SESSION['Level'] == '4') {
    echo "
			&nbsp;&nbsp;&nbsp;<a href='shop.php?tsearch'>SEARCH</a>
			&nbsp;&nbsp;&nbsp;<a href='shop.php?utr'>UPDATE TRANSACTION</a>
			&nbsp;&nbsp;&nbsp;<a href='shop.php?dextr'>EXTRACT DEBTOR</a>
			&nbsp;&nbsp;&nbsp;<a href='shop.php?Nsexpense'>EXPENSE</a>
			&nbsp;&nbsp;&nbsp;<a href='shop.php?spaos'>PROFITABILITY CHECK</a>
			&nbsp;&nbsp;&nbsp;<a href='shop.php?Newshoi'>SHOP MANAGEMENT</a>
			&nbsp;&nbsp;&nbsp;<a href='shop.php?vhst'>VIEW SUMMARY</a>";
  }
  else{
    echo "";
  }
  ?>
</form>
		
</div>	<!--end of content -->
<div id="sidebar">
<div id="chat">
<!-- Display chat messages here -->

</div>	<!-- end chat --> 		
</div><!-- end sidear -->
</div><!-- end sidear -->
<div id="footer">
<p>Copyright &copy;  2023 RSSB</p>
<a href="index.php?privacy">Privacy Policy</a>
<a href="https://twitter.com/robinsbureau"><i class="fab fa-twitter"></i></a>
<a href="https://www.telegram.org/robinsbureau/"><i class="fab fa-telegram"></i></a>
</div><!--end #footer -->


	
</body>
</html>	