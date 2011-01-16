@charset "UTF-8";

<?php
/*** set the content type header ***/
header("Content-type: text/css");

?>

* {
  margin: 0;
  padding: 0;
  border: 0;
}

body {
	color: #3d3d3d;
	padding: 0px;
	font-family: "Courier New", Courier, monospace;
	background-color: #fffefe;
	line-height: 1em;
	height: 100%;
}

/* ---------- text ---------- */

#container {
/* 	border-style: solid; */ 	                         
 	margin: 20px 20px 0px 20px;	                   
	border-left-width: 0px;
	border-bottom-width: 0px;                         
	border-right-width: 1px;
	border-top-width: 0px;
	border-style: dotted;
	width: 760px;
}

aside {
	position: fixed;
	/*line-height: 1.5em;*/
	height: 100%;
	width: 100px;
	background-color: #242424; 	
}

aside li {
	list-style: none;
}

nav {
	text-align: right;
	line-height: 1.5em;
	margin-top: 50px;
	font-style: normal;
	font-weight: normal;
	color: #fefeff;
	font-size: 0.9em;
	margin-right: 5px;

}

nav a {
	text-decoration: none;
	color: #fefefe;
}

nav a:hover {
	background-color: white;
	color: black;
}

section {
	width: 640px;
	margin-left: 120px;
}

header {
	padding-left: 10px;
	color: black;
	background-image: url('/images/tilegeneric.jpg');
	background-repeat: repeat;
	line-height: 110px;
	text-align: left;
	vertical-align: bottom;
	height: 85px;
	width: 630px;
}

article {
	font-size: 0.8em;
	padding-top: 20px;
	padding-left: 0px;
	margin-left: 0px;
	background-color: white;
	width: 600px;
}


article a {
	color: #a2a63d;
	text-decoration: none;
	
}

article a:hover {
	background-color: #f9fd73;
	color: #000;
}

h1 {
	font-style: normal;
	font-weight: normal;
	letter-spacing: 5px;
	font-size: 1.7em;
}

h2 {
	padding-bottom: 20px;
	padding-top: 30px;
	letter-spacing: 4px;
	font-style: normal;
	font-weight: normal;
	color: #222c1a;
	font-size: 1.5em;
}

h3 {
	padding-bottom: 10px;
	padding-top: 10px;
	line-height: 20px;
	letter-spacing: 3px;
	font-style: normal;
	font-weight: normal;
	color: #262625;
	font-size: 1.2em;
}

.date {
	color: #98b327;
}

img.home {
	display: block;
	margin-right: auto;
	margin-left: auto;
}

#footer {
	text-transform: uppercase;
	font-size: 0.5em;
	text-align: right;
	clear: both;
}

.center {
	display: block;
	margin-left: auto;
	margin-right: auto;
}

.title {
	line-height: 2em;
	color: #232323;
	font-size: 1.1em;
}

td {
	width: 24px;
	max-width: 25px;
}

dl {
	line-height: 1.6em;
}

.right {
/* 	clear: both; */
	float: right;
	margin-right: -40px;
}

.left {
	float: left;
	margin: 0px;
}

.clear {
	clear: right;
}

.highlight {
	background-color: #fffa96;
}

#applet {
}


.article {
	padding: 20px;
}

.tbpadding {
	padding-bottom: 20px;
	padding-top: 30px;
}

li {
	list-style-type: none;
}

p {
	padding-bottom: 5px;
	padding-top: 5px;
}

