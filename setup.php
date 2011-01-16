<?php

include_once('./include.php');
 
$db_server = mysql_connect(DB_HOST, DB_USER, DB_PASS);
if(!$db_server) die("Can't connect to server: " . mysql_error());
mysql_select_db(DB);

$query = "CREATE TABLE users(
id INT NOT NULL AUTO_INCREMENT,
user VARCHAR(40),
pass VARCHAR(40),
PRIMARY KEY(id)) ENGINE MyISAM;";
$result1 = mysql_query($query);
if($result1) echo('Users table created...' . "<br />\n");
else die(mysql_error());

/* create homepages table */

$query = "CREATE TABLE homepage(
id INT NOT NULL AUTO_INCREMENT,
no INT(4),
name VARCHAR(30),
date DATETIME,
info TEXT,
path TINYTEXT,
type VARCHAR(5),
PRIMARY KEY(id)) ENGINE MyISAM;";
$result2 = mysql_query($query);
if($result2) echo('HomePath table created...' . "<br />\n");
else die(mysql_error());

/* create page table */

$query = "CREATE TABLE pages (
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(20),
type VARCHAR(10),
PRIMARY KEY (id)
) ENGINE MyISAM;";
$result3 = mysql_query($query);
if($result3) echo('Pages table created...' . "<br />\n");
else die(mysql_error());

if($result1 && $result2 && $result3) echo("All OK");
else echo("Not OK");

mysql_close($db_server);


?>