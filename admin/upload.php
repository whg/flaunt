<?php 

include_once('./afunctions.php');
check_login();

$pagetitle = 'Upload';

$message = '';


/* - - - - - - - UPLOAD FILE SCRIPT - - - - - - - -  */

$uploadDir = HOME . 'content/uploads/';

if(isset($_POST['uploadfile'])) {
	$fileName = $_FILES['file']['name'];
	$tmpName = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileType = $_FILES['file']['type'];
	
	$filePath = $uploadDir . $fileName;
	
	$result = move_uploaded_file($tmpName, $filePath);
	
	if (!$result) {
		$message .= p_wrap("Error uploading, are you sure you chose a something to upload?");	
	}
	else {
		$message .= p_wrap("File uploaded successfully");
	}
}


get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

//display all messages...
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>


<section class="add">
<h3>Upload file</h3>
<form method="post" action="upload.php" enctype="multipart/form-data" >
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<label>File: </label><input type="file" name="file" /><br />
<input type="submit" value="Upload" name="uploadfile" />
</form>
</section>


<?php
mainsection('e');

get_footer();

?>