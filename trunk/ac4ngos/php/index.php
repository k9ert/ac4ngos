<?
session_start();

require("accrp.php");

if($username != "") {
	$db = getDBConnection();
	$bleh = (int) $username;
	if($bleh == 0) {
		$user_result = mysql_query("SELECT * FROM Users where Username='$username'", $db);
	} else {
		$user_result = mysql_query("SELECT * FROM Customers where CustomerID=$username", $db);
		$customer = 1;
	}
	checkMySQLError();
	$user_row = mysql_fetch_array($user_result);
	if(($user_row["Password"] == $password) and (mysql_num_rows($user_result)) and ($password != "")) {
		session_start();
		$sess_user = $username;
		$sess_customer = $username;
		$sess_lang = $user_row["Language"];
		$sess_name = $user_row["RealName"];
		$sess_admin = $user_row["Admin"];
		if($customer != 1) {
			session_register("sess_user");
		} else {
			session_register("sess_customer");
		}
		session_register("sess_lang");
		session_register("sess_name");
		session_register("sess_admin");
		beginDocument("Session Began", $sess_name);
		echo "Got User, session started, redirecting!<br>";
		if($customer != 1) {
			echo "<a href='main.php'>Main</a>";
			echo "<script language='JavaScript'>window.location='main.php'</script>";
		} else {
			echo "<a href='publicprofile.php'>Main</a>";
			echo "<script language='JavaScript'>window.location='publicprofile.php'</script>";
		}
	} else {
		echo "<script language='JavaScript'>window.location='index.php?login=failed'</script>";
	}
} else {

beginDocument("Login", "Not Logged In");
session_destroy();
?>
<form name='login' method='POST' action='<?$PHP_SELF?>'>
<? openForm("login", $PHP_SELF); ?>
<table width='100%' height='50%' cellpadding=0 cellspacing=0 border=0>
 <tr>
  <td valign='center' align='center'>
<? beginPrettyTable("2", $lLogin); ?>
<tr>
 <td>Username:</td>
 <td><input type='text' name='username' value=''></td>
</tr>
<tr>
 <td>Password:</td>
 <td><input type='password' name='password' value=''></td>
</tr>
<? makeSubmitter(); ?>
<? endPrettyTable(); ?>
  </td>
 </tr>
</table>
<? closeForm(); ?>
<?
}
endDocument();
?>
