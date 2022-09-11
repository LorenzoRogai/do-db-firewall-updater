# DO Database Firewall Updater
A simple PHP script to keep Digital Ocean Database Firewall up to date with your current IP

## How to

Add the following rule inside your crontab

* * * * * root php /home/pi/do-db-firewall-updater.php