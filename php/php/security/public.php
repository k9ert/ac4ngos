<?
# This chunk of code checks to see if a customer has been registered in the
# session, if not, it dies.  Security!

if(!(session_is_registered("sess_customer"))) {
	beginDocument("Security", "Not Logged In");
	beginPrettyTable(1, "Security");
	echo "<tr><td class='data'>You are not logged in.  Please <a href='index.php'>Login</a></td></tr>";
	endPrettyTable();
	die;
}
?>
