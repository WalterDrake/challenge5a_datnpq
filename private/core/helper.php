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

function upload_file($FILES, $id = "")
{
	if (count($FILES) > 0) {

		// allowed file types
		$allowed[] = "application/pdf";
		$allowed[] = "application/msword";
		$allowed[] = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
		$allowed[] = "application/vnd.ms-excel";
		$allowed[] = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
		$allowed[] = "application/vnd.ms-powerpoint";
		$allowed[] = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
		$allowed[] = "text/plain";
		$allowed[] = "application/zip";

		foreach ($_FILES['assignment_files']['name'] as $key => $name) {
			if ($_FILES['assignment_files']['error'][$key] == 0 && in_array($_FILES['assignment_files']['type'][$key], $allowed)) {

				if (Auth::getRole() == "Teacher" || Auth::getRole() == "Administrator") {
					$folder = "assets/uploads/" . $id . "/assignments/";
				} else {
					$folder = "assets/uploads/" . $id . "/submits/" . Auth::getUser_id() . "/";
				}

				if (!file_exists($folder)) {
					mkdir($folder, 0777, true);
				}

				$destination = $folder . basename($_FILES['assignment_files']['name'][$key]);
				if (move_uploaded_file($_FILES['assignment_files']['tmp_name'][$key], $destination)) {
					$uploaded_files[] = $destination;
				}
			}
		}
		return $uploaded_files;
	}
	return false;
}
