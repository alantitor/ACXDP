<?php
/*
 *
 */
	function pxd($file_type, $p1, $p2, $p3, $p4)
	{
		include 'processXml.php';
		include 'processDb.php';
		include 'output.php';

		$folder_path = "xmlFolder/";
		
		//if (strcmp($file_type, "_4X") == 0) {
		//	$folder_path = "xmlFolder/4X/";
		//} else if (strcmp($file_type, "_4") == 0) {
		//	$folder_path = "xmlFolder/4/";
		//} else {
		//	//
		//}


		/* open xml files */

		$xml_name_array = getFiles($folder_path);
		$xml_eng_entry;
		$xml_entry_array = openFiles($xml_name_array);
		$xml_file_number = count($xml_entry_array);
		//echo "xml file number: $xml_file_number<br>";
		//for ($i = 0; $i < $xml_file_number; $i++) {
		//	echo "$xml_name_array[$i]<br>";
		//}

		foreach ($xml_name_array as $xn) {
			//echo "file name: " . xmlLang($xn, $xn) . ", $xn<br>";
			if (strcmp(xmlLang($xn, $xn), "eng") == 0) {
				//echo "I am Eng: ". $xn . "<br>";
				$xml_eng_entry = openFile($xn);
				break;
			}
		}


		/* connect DB */

		$connect = connectDb($p1, $p2, $p3, $p4);


		/* travel xml structure */
		/*
		 * Algorithm:
		 *
		 * (1) process eng xml file
		 * (2) process other xml files
		 */

		$result = "";
		$message = "";
		$temp = "";

		foreach ($xml_eng_entry->item as $item) {
			$temp .= travelXml("eng", $item, $connect, $file_type);
		}
		if (strlen($temp) > 0) {
			$message .= "<div class='gen-lang'>$temp</div>";
		}
	
		$temp = "";
		for ($i = 0; $i < $xml_file_number; $i++) {
			$xml_lang = xmlLang($file_type, $xml_name_array[$i]);
			if (strcmp($xml_lang, "eng") == 0) {continue;}
			foreach ($xml_entry_array[$i]->item as $item)	{
				$temp .= travelXml($xml_lang, $item, $connect, $file_type);
			}

			if (strlen($temp) > 0) {
				$message .= "<div class='gen-lang'>$temp</div>";
			}
			$temp = "";
		}	


		$result = "<div class='gen-lang-wrapper'>$message</div>";
		$result .= "<hr>";


		/* close */

		mysqli_close($connect);

		return $result;
	}

	
	/*
	 * travel xml file and return 
	 *
	 * @xml_item: xml entry.  item tag.
	 * @xml_lang: xml file language version
	 * @connect: DB entry
	 * @type: machine structure type
	 * @return: elements which be modified. | string.
	 */
	function travelXml($xml_lang, $xml_item, $connect, $type)
	{
		$proj_id = getDbProjectId($connect, $type);
		$lang_id = getDbLangId($connect, $xml_lang);
		$item_id = getDbItemId($xml_item->internalName, $connect, $type);  // it is array, a app may have different data.


		$message = "";
		$temp = "";

		if (count($item_id) > 0) {
			foreach ($item_id as $id) {
				$temp = travelTag($id, $proj_id, $lang_id, $xml_item, $connect, $type);
				if (strlen($temp) > 0) {
					$temp = "<div class='gen-item-id'>$temp</div>";
					$message .= $temp;
				}
			}
		} else {
			$temp = travelTag(0, $proj_id, $lang_id, $xml_item, $connect, $type);
                        if (strlen($temp) > 0) {
                        	$temp = "<div class='gen-item-id'>$temp</div>";
                                $message .= $temp;
                        }
		}


		if (strlen($message) > 0) {
			$message = "<div class='gen-item-wrapper'>$message</div>";
		}

		return $message;
	}

	/*
	 *
	 */
	function travelTag($app_id, $proj_id, $lang_id, $xml_item, $connect, $type)
	{
		$message = "";
		$temp = "";

		// grep HD_Station app out

		if (strlen(getNodeName($xml_item->class)) > 0) {
			return $message;			
		}

		if (strcmp("HD_Station", $xml_item->internalName) == 0) {
			return $message;
		}

                if (strcmp("CodexPack", $xml_item->internalName) == 0) {
			return $message;
                }


		// normal app

		$temp .= do_internalName($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		// get app id
		$sql = "SELECT ID FROM qpkg_item WHERE Name_internal = '$xml_item->internalName' AND Project_ID = '$proj_id'";
		$res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);
		$app_id = $row['ID'];

		$temp = do_language($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_description($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_name($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_category($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_type($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_icon80($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_icon100($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_fwVersion($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_version($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_snapshot($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_snapshotSmall($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_platform($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_publishedDate($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_maintainer($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_developer($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_developerLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_forumLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_wikiLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);

		$temp .= do_tutorialLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect);


		$message = $temp;

		return $message;
	}
?>
