<?php

// remain value after submit
function get_var($key, $default = "")
{

	if (isset($_POST[$key])) {
		return $_POST[$key];
	}

	return $default;
}

// escape html
function esc($var)
{
	return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}


function get_images($image)
{
	$id = Auth::getUser_id();
	$imagePath = dirname(__DIR__, 2) . "/public/assets/uploads/" . $id . "/avatar/" . $image;
	if (!file_exists($imagePath)) {
		$image = ASSETS . "/dinosaur.png";
	} else {
		$image = ASSETS . "/uploads/" . $id . "/avatar/" . $image;
	}
	return $image;
}


function upload_image($FILES, $id = "")
{
	if (count($FILES) > 0) {

		//we have an image
		$allowed[] = "image/jpeg";
		$allowed[] = "image/png";
		if ($FILES['avatar']['error'] == 0 && in_array($FILES['avatar']['type'], $allowed)) {
			$folder = "assets/uploads/" . $id . "/avatar/";
			if (!file_exists($folder)) {
				mkdir($folder, 0777, true);
			}
			$destination = $folder . time() . "_" . $FILES['avatar']['name'];
			move_uploaded_file($FILES['avatar']['tmp_name'], $destination);
			return $destination;
		}
	}

	return false;
}
