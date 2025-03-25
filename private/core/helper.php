<?php

// remain value after submit
function get_var($key)
{
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return '';
}

// escape html
function esc($var)
{
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}


function get_images($image)
{
    if (!file_exists($image)) {
        $image = ASSETS . "/dinosaurs.png";
    }
    return $image;
}


function upload_image($FILES)
{
	if(count($FILES) > 0)
	{

		//we have an image
		$allowed[] = "image/jpeg";
		$allowed[] = "image/png";

		if($FILES['image']['error'] == 0 && in_array($FILES['image']['type'], $allowed))
		{
			$folder = "uploads/";
			if(!file_exists($folder)){
				mkdir($folder,0777,true);
			}
			$destination = $folder . time() . "_" . $FILES['image']['name'];
			move_uploaded_file($FILES['image']['tmp_name'], $destination);
			return $destination;
		}
		
	}

	return false;
}