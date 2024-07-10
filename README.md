# MySQL Replication Notification
## Php code for querying MySQL Replication Status and notifying the sysadmin via email when replication fails.

Cronjob:
+ ```* * * * * /usr/bin/php -f /home/user/scripts/check_mysql_replication.php```
