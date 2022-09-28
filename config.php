<?php
/* DATABASE CONFIGURATION */
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'mysql_local');
define('PORT', 33061);

/* connect to mysql db */
$dbConn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, PORT);
if ($dbConn -> connect_errno) {
    echo "Failed connecting to MySQL: " . $dbConn -> connect_error;
    exit();
}
?>
