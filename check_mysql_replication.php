<?php
require_once (dirname(__FILE__)."/classes/class.db.php");
require_once (dirname(__FILE__)."/classes/class.simple_api.php");
require_once (dirname(__FILE__)."/classes/class.sendgrid.php");

$SG = new SendGridAPI();
$db = new db('localhost', 'root', 'password');

$results = $db->query("SHOW SLAVE STATUS;")->fetchArray();
if (isset($results)) {
	if ($results['Slave_IO_Running'] != "Yes" ||
		$results['Slave_SQL_Running'] != "Yes") {

		if (!@fopen('/tmp/check_mysql_replication.lock', 'r')) {
			// PREVENTING EMAIL ALERTS UNTIL REPLICATION LOCK CLEARED
			print "Replication Failure";
			$fp = fopen('/tmp/check_mysql_replication.lock', 'c');
			$date = date('Y-m-d H:i:s');
			$SG->SendEmail(
				"user@emai.il",
				"FirstName LastName",
				"Server - MySQL Replication Failure",
				"[$date] - SQL Replication Failure",
				"noreply@domain.com",
				"FromName");
			exit;			
		}		
	} else {
		@unlink('/tmp/check_mysql_replication.lock');
	}
}
?>
