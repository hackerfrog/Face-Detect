<?php

include 'SimpleImage.php';

if ($_POST['upload']) {
	if (empty($_FILES['image']['tmp_name'])) {
	header("location: index.php?error=empty");
	} else {
		$extension = array("jpg", "png");

		$file_name=$_FILES["image"]["name"];
		$ext = pathinfo($file_name, PATHINFO_EXTENSION);

		$only_name = md5($file_name);	#uniqid('', false);
        $new_name = $only_name.'.'.$ext;

        if(in_array($ext,$extension)) {

            if(move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/".$new_name)) {
            	global $success;
            	$success = 0;

				$edit = new SimpleImage('uploads/'.$new_name);
				$edit->fit_to_width(800)->save('uploads/'.$new_name);
			    header("location: data.php?img=".$new_name);
            } else {
            	global $success;
            	$success = 1;
            	header("location: index.php?error=fail");
            }

        }
    }
} else {
	header("location: index.php");
}

?>