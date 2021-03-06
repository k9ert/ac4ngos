<?
# accrp Settings
#

# This file shouldn't be in a public accessable Folder of security reasons.
# In the moment, it is in an public folder because of easier installation


#----------------------------------------------------------------------------
# Database Settings
#----------------------------------------------------------------------------

# Persistent database connections
$PERSISTENT = "On";

# Database Host
$DBHOST = "localhost";

# Database User
$DBUSER = "ac4ngos";

# Database Password
$DBPASSWORD = "accrp";

# Database for accrp to use
$DBNAME = "ac4ngos";

#----------------------------------------------------------------------------
# Misc Settings
#----------------------------------------------------------------------------

# The Path, where sessions get stored
# Adjusting only for sourceforge
if($DBHOST=="mysql.sourceforge.net")
	$SESSION_PATH = "/tmp/persistent/ac4ngos";


#----------------------------------------------------------------------------
# Customization Settings
#----------------------------------------------------------------------------
# Some of the color settings are in the accrp.css file

# Name you want pasted all over ac4ngos interface, like a Company or something
$BRAND = "ac4ngos";

# Color of the topbar where the user and brand line
$TOPBAR = "mediumslateblue";

# Color of the page background
$BACKGROUND = "white";

# Color of the table headers and borders
$TABLEBORDER = "mediumslateblue";

# Color of the tabble innards
$INNERTABLE = "lightgrey";

?>
