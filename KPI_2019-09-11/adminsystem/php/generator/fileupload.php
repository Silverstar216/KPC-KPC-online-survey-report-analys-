<?php
/**
 * @param : folder
*/
$target_dir = filter_input(INPUT_GET, 'folder');
$target_dir = trim($target_dir);

$target_file = $target_dir . basename($_FILES["upfile"]["name"]);

$allowed = array("hwp", "doc", "docx", "ppt", "pptx", "xls", "xlsx", "pdf", "jpg", "png", "gif", "bmp", "tif", "tiff", "txt", "glh", "glo", "glp", "gle", "htm");
 
$uploadOk = 1;
$file_extension = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}

// Check file size
//if ($_FILES["upfile"]["size"] > 500000) {
//    echo "Sorry, your file is too large.";
//    $uploadOk = 0;
//}

// Allow certain file formats
if(!in_array($file_extension, $allowed)) {
    echo "Sorry, your file is not surported.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	echo "FALSE";
// if everything is ok, try to upload file
} else {
	if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $target_file)) {
		echo "TRUE";
	} else {
		echo "FALSE";
	}
}
?>