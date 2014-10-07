<?php
/*
 * Upload xml files to server
 * Date: 2014/08/16
 * Author: Alan Ho
 *
 * @file: php file array
 * @return: system information
 */
function uploadFile($file)
{
	include 'output.php';
	// xml folder path
	$folder_path = "xmlFolder/";
	// xml files count
	$file_count = count($file['upload']['name']);
	// files information array, 1D array
	$file_info = array();
	$result_info = "";

	// lool through each file
	for ($i = 0; $i < $file_count; $i++) {
		$extension = end(explode(".", $file["upload"]["name"][$i]));  // get file extensions

		if ($extension == "xml") {
			if ($file["upload"]["error"][$i] > 0) {
				array_push($file_info,  transFinfoToHtml(0, $file["upload"]["error"][$i], $file["upload"]["name"][$i], $file["upload"]["type"][$i], $file["upload"]["size"][$i]/1024));
			} else {
                        	if (file_exists($file["upload"]["name"][$i])) {
					array_push($file_info,  transFinfoToHtml(2, $file["upload"]["error"][$i], $file["upload"]["name"][$i], $file["upload"]["type"][$i], $file["upload"]["size"][$i]/1024));
                        	} else {
					$tn = $file["upload"]["tmp_name"][$i];
					$n = $file["upload"]["name"][$i];
					move_uploaded_file($tn, $folder_path . $n);  // copy file from PHP upload template folder to target folder
					array_push($file_info,  transFinfoToHtml(1, $file["upload"]["error"][$i], $file["upload"]["name"][$i], $file["upload"]["type"][$i], $file["upload"]["size"][$i]/1024));
                        	}
			}
		} else {  // you can not pass!
			array_push($file_info, transFinfoToHtml(-1, -1, $file["upload"]["name"][$i], "", ""));
		}

	}

	$result_info = output_info($file_info);

	return $result_info;
}
?>
