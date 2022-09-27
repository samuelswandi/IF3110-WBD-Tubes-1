<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'mysql_local');
define('PORT', 3306);
 
/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, PORT);
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
?>