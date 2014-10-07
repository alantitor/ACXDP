<?php
/*
 * File: processDb.php
 *
 * process DB library
 *
 * Author: Alan Ho
 * Time: 2014/07/22
 */


	/*
	 * connect to DB
	 *
	 * @return: DB entry
	 */
	function connectDb($p1, $p2, $p3, $p4)
	{
		//$con = mysqli_connect("localhost", "root", "abcde", "qpkg"); 
		$con = mysqli_connect($p1, $p2, $p3, $p4);
		mysqli_query($con, "SET NAMES 'UTF8'");

		if (mysqli_connect_error()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return null;
		} else {
			return $con;
		}
	}

	/*
	 * get project id from "project" table
	 *
	 * @connect: DB entry
	 * @type: machine structure type
	 * @return: ID
	 */
	function getDbProjectId($connect, $type)
	{
		if (strcmp($type, "_4X") == 0) {
			$type = "NAS(4X)";
		} else if (strcmp($type, "_4") == 0) {
			$type = "NAS(4)";
		} else {
			//
		}

		$sql = "SELECT ID FROM project WHERE Name = '$type'";
		$result = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($result);
		return $row['ID'];
	}

	/*
	 * get langage id form "language" table
	 *
	 * @connect: Db entry
	 * @lang: lanaguage short name
	 * @return: lang id
	 */
	function getDbLangId($connect, $lang)
	{
		$lang_id = "";
		$sql = "SELECT ID FROM language WHERE Short_Name = '$lang'";
		$result = mysqli_query($connect, $sql);
		while ($row = mysqli_fetch_array($result)) {
			$lang_id = $row['ID'];
		}

		return $lang_id;
	}

	/*
	 * get item id from "qpkg_item" table
	 *
	 * @internal_name: name
	 * @connect: DB entry
	 * @type: machine structure type
	 * @return: item id | array, maybe it will return many items
	 */
	function getDbItemId($internal_name, $connect, $type)
	{
		$result_array = array();

		$project_id = getDbProjectId($connect, $type);
		$result = mysqli_query($connect, "SELECT ID FROM qpkg_item WHERE Name_internal = '$internal_name' AND Project_ID = '$project_id'");
		while ($row = mysqli_fetch_array($result)) {
			array_push($result_array, $row['ID']);
			//echo "-" . $row['ID'] . "<br>";
		}

		return $result_array;
	}
	
	/*
	 * get category xml name by id
	 *
	 * @connect: DB entry
	 * @cate_id: category id
	 */
	function getDbCategoryName($connect, $cate_id)
	{
		$cate_name = "";
		$sql = "SELECT Name_XML from qpkg_category WHERE ID = $cate_id";
		$result = mysqli_query($connect, $sql);
		while ($row = mysqli_fetch_array($result)) {
			$cate_name = $row['Name_XML'];
		}

		return $cate_name;
	}

	/*
	 * get category id by xm name
	 *
	 * @connect: DB entry
	 * @xml_cate_name: xml category name
	 */
	function getDbCategoryId($connect, $xml_cate_name)
	{
		$cate_id = "";
                $sql = "SELECT ID FROM qpkg_category WHERE Name_XML LIKE '$xml_cate_name'";
                $result = mysqli_query($connect, $sql);
                while ($row = mysqli_fetch_array($result)) {
                        $cate_id = $row['ID'];
                }

		return $cate_id;
	}

	/*
	 * get type name by type id
	 *
	 * @connect: DB entry
	 * @type_id: type id
	 */
	function getDbTypeName($connect, $type_id)
	{
		$type_name = "";
		$sql = "SELECT Name FROM qpkg_type WHERE ID = $type_id";
		$result = mysqli_query($connect, $sql);
		while ($row = mysqli_fetch_array($result)) {
			$type_name = $row['Name'];
		}

		return $type_name;
	}

        /*
         * get type id by nme
	 *
	 * @connect: DB entry
	 * @type_name: type name
         */
        function getDbTypeID($connect, $type_name)
        {
                $type_id = "";
                $sql = "SELECT ID FROM qpkg_type WHERE Name LIKE '$type_name'";
                $result = mysqli_query($connect, $sql);
                while ($row = mysqli_fetch_array($result)) {
                        $type_id = $row['ID'];
                }
                
		return $type_id;
        }

        /*
         * get platform id
	 * 
	 * @connect: DB entry
	 * @pl_id_name: platform name
         */
        function getDbPlatID($connect, $pl_id_name)
        {
                $type_id = -1;
                $sql = "SELECT ID FROM qpkg_platform WHERE PlatformID LIKE '$pl_id_name'";
                $result = mysqli_query($connect, $sql);
                while ($row = mysqli_fetch_array($result)) {
                        $type_id = $row['ID'];
                }

                return $type_id;
        }

        /*
         * get platform excl id
	 *
	 * @connect: DB entry
	 * @pl_id_name: platfomr excl name
         */
        function getDbPlatExID($connect, $pl_id_name)
        {
                $type_id = "";
                $sql = "SELECT ID FROM qpkg_platform_excl WHERE Name LIKE '$pl_id_name'";
                $result = mysqli_query($connect, $sql);
                while ($row = mysqli_fetch_array($result)) {
                        $type_id = $row['ID'];
                }

                return $type_id;
        }

	/*
	 * @par: 1, get ID. 2, get short name
	 */
	function getDbLangDataByName($connect, $ln, $par)
	{
		$tt = "";

		$ln = trim($ln);

		if (strcmp($ln[0], " ") == 0) {
			$ln = substr($ln, 1);
		}

		if (strcmp($ln[0], " ") == 0) {
			$ln = substr($ln, 1);
		}

		$cc = count($ln);
		if (strcmp($ln[$cc - 1], " ") == 0) {
			$ln = substr($ln, -1);
		}

		// special case
		switch ($ln) {
			case "簡中":
				$ln = "简体中文";
				break;
			case "繁中":
				$ln = "繁體中文";
				break;
			case "簡體中文":
				$ln = "简体中文";
				break;
			case "简中":
				$ln = "简体中文";
				break;
			case "Polish":
				$ln = "Polski";
				break;
			case "Spanish":
				$ln = "Español";
				break;
			case "Swedish":
				$ln = "Svenska";
				break;
			case " ":
				$ln = "?";
				break;
			default:
				break;
		}

                $sql = "SELECT * FROM language WHERE Name LIKE '$ln'";
                $result = mysqli_query($connect, $sql);
		if (mysqli_num_rows($result) == 0) {
			return "?";
		}

                while ($row = mysqli_fetch_array($result)) {
			if ($par == 1) {
				$tt = $row['ID'];
			} else if ($par == 2) {
				$tt = $row['Short_Name'];
			} else {
				//
			}
		}

		return $tt;
	}

	/*
	 *
	 */
        function do_internalName($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";
		$found = 0;

                if (strlen(getNodeName($xml_item->internalName)) == 0) {
                        $message = outputQueryInfo(2, "internalName", $lang_id, $app_id, 0, "", "", "", "", "", "No internalName tag.", 2);
                        return $message;
                }


                // check app is exists or not
                $sql = "SELECT ID FROM qpkg_item WHERE Name_internal = '$xml_item->internalName' AND Project_ID = '$proj_id'";
                $res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);
		$found = mysqli_num_rows($res);		


                if ($found == 0) {
                        // insert app into DB
                        $sql_ud = "INSERT INTO qpkg_item (Project_ID, Name, Name_internal) VALUES ('$proj_id', '$xml_item->name', '$xml_item->internalName')";
                        $res_ud = mysqli_query($connect, $sql_ud);

                        $sql_ud = "SELECT ID FROM qpkg_item WHERE Name_internal = '$xml_item->internalName' AND Project_ID = '$proj_id'";
                        $res_ud = mysqli_query($connect, $sql_ud);
			$row_ud = mysqli_fetch_array($res_ud);

                        $sql_ud = "UPDATE qpkg_item SET HD_Station = '0', Tutorial_Type = '0' WHERE ID = '" . $row_ud['ID'] . "'";
                        $res_ud = mysqli_query($connect, $sql_ud);

                        $tempmes = outputQueryInfo(1, "internalName", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row_ud['ID'], "Project_ID, HD_Station, Name, Name_internal", "", "$proj_id, 0, $xml_item->name, $xml_item->internalName", "", 0);

			// add new langauge data
			$sql_ud = "SELECT ID FROM language";
			$res_ud = mysqli_query($connect, $sql_ud);
			$lang_count = mysqli_num_rows($res_ud);
			for ($t = 1; $t <= $lang_count; $t++) {
				$sql_ud2 = "INSERT INTO qpkg_item_export_lang (Item_ID, Lang_ID, Status) VALUES ('" .  $row_ud['ID'] . "', '$t', '2')";
				$res_ud2 = mysqli_query($connect, $sql_ud2);
				$sql_ud2 = "INSERT INTO qpkg_item_language (Item_ID, Lang_ID, Status) VALUES ('" . $row_ud['ID'] . "', '$t', '2')";
				$res_ud2 = mysqli_query($connect, $sql_ud2);
			}
                } else {
			//
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
	 *
	 */
	function do_language($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
	{
		/*
		 * Algorithm:
	 	 *
		 * (1) xml file language version: if a app is in this file, 'Status' field in 'qpkg_item_export_lang' table is '1'.
		 * (2) value is language list in each item tab mean it have local language for this country.
		 */

		$message = "";
		$tempmes = "";

		if (strlen(getNodeName($xml_item->language)) == 0) {
			$message = outputQueryInfo(2, "language", $lang_id, $app_id, 0, "", "", "", "", "", "No language tag.", 2);
			return $message;
		} 


		// part 1


		$sql = "SELECT ID, Status FROM qpkg_item_export_lang WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
		$res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);
		if ($row['Status'] == 2) {
			$sql_ud = "UPDATE qpkg_item_export_lang SET Status = '1' WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
			$res_ud = mysqli_query($connect, $sql_ud);
			$tempmes = outputQueryInfo(1, "language", $lang_id, $app_id, 1, "qpkg_item_export_lang", "ID" . $row['ID'], "Status", "2", "1", "", 0);
		}

		if (strlen($tempmes) > 0) {$message .= $tempmes; $tempmes = "";}


		// part 2


		// get xml language list. ex: English...
		$xml_lang_name_list = multiexplode(array(",", ", ", ".", "，", "、", "Language:"), $xml_item->language);
		$xml_lang_id_list = array();

		// get DB language data. 'qpkg_item_language' table
		$sql = "SELECT ID, Item_ID, Lang_ID, Status FROM qpkg_item_language WHERE Item_ID = '$app_id'";
		$res_db_lang = mysqli_query($connect, $sql);
		
		$xml_have_eng_flag = false;
		$db_found_flag = false;
		
		
		// trans language list from name to id
		foreach ($xml_lang_name_list as $xll) {
			$tt = getDbLangDataByName($connect, $xll, 2);
			if (strcmp($tt, "?") == 0) {
				$ex = "Can not identify this token '$xll' in language list.";
				$message .= outputQueryInfo(2, "language", $lang_id, $app_id, 0, "", "", "", "", "", $ex, 1);
			} else {
				if (strcmp($tt, "eng") == 0) {
					$xml_have_eng_flag = true;
				}

				array_push($xml_lang_id_list, getDbLangId($connect, $tt));
			}
		}


		// start


		if ($xml_have_eng_flag == false) {  // switch 'qpkg_item_language' table 'Status' field from 2 to 1 where 'Lang_ID' = 1
                        $sql = "SELECT ID, Status FROM qpkg_item_language WHERE Item_ID = '$app_id' AND Lang_ID = '1'";
                        $res = mysqli_query($connect, $sql);
                        $row = mysqli_fetch_array($res);
			if ($row['Status'] == 2) {
				$sql = "UPDATE qpkg_item_language SET Status = '1' WHERE Item_ID = '$app_id' AND Lang_ID = '1'";
				$res = mysqli_query($connect, $sql);
				$tempmes = outputQueryInfo(1, "language", $lang_id, $app_id, 1, "qpkg_item_language", "ID: " . $row['ID'], "Status", "2", "1", "", 0);
			}
		}

		foreach ($xml_lang_id_list as $xlil) {
			$sql = "SELECT ID, Item_ID, Lang_ID, Status FROM qpkg_item_language WHERE Item_ID = '$app_id' AND Lang_ID = '$xlil'";
			$res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);
			if ($row['Status'] == 2) {
				$sql_ud = "UPDATE qpkg_item_language SET Status = '1' WHERE Item_ID = '$app_id' AND Lang_ID = '$xlil'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$tempmes = outputQueryInfo(1, "language", $lang_id, $app_id, 1, "qpkg_item_language", "ID: " . $row['ID'], "Status", "2", "1", "", 0);
			}
		}


		if (strlen($tempmes) > 0) {$message .= $tempmes;}		

		return $message;
	}

	function multiexplode($delimiters,$string)
	{
    		$ready = str_replace($delimiters, $delimiters[0], $string);
    		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}

	function do_name($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
	{
		$message = "";
		$tempmes = "";

		if (strlen(getNodeName($xml_item->name)) == 0) {
                        $message = outputQueryInfo(2, "name", $lang_id, $app_id, 0, "", "", "", "", "", "No name tag.", 2);
                        return $message;
                }


		if ($lang_id == 1) {  // english at 'qpkg_item' table
			$sql = "SELECT * FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			$res = mysqli_query($connect, $sql);

			if (mysqli_num_rows($res) > 0) {
				while ($row = mysqli_fetch_array($res)) {
					if (strcmp($xml_item->name, $row['Name']) != 0) {
						$sql_ud = "UPDATE qpkg_item SET Name = '$xml_item->name' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
						$res = mysqli_query($connect, $sql_ud);
						$tempmes = outputQueryInfo(1, "name", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Name", $row['Name'], $xml_item->name, "", 0);
					}
				}
			} else {
				
			}
		} else {  // other language. use language list filter data
			$sql = "SELECT ID, Content FROM qpkg_item_name WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
			$res = mysqli_query($connect, $sql);
		
			if (mysqli_num_rows($res) > 0) {
				$row = mysqli_fetch_array($res);
				if (strcmp($row['Content'], $xml_item->name) != 0) {
					$sql_ud = "UPDATE qpkg_item_name SET Content = '$xml_item->name' WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
					$res_ud = mysqli_query($connect, $sql_ud);
					$tempmes = outputQueryInfo(1, "name", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Name", $row['Name'], $xml_item->name, "", 0);
				}
			} else {
				$sql_ud = "INSERT INTO qpkg_item_name (Item_ID, Lang_ID, Content) VALUES ('$app_id', '$lang_id', '$xml_item->name')";
				$res_ud = mysqli_query($connect, $sql_ud);
				$sql_ud = "SELECT ID FROM qpkg_item_name WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);
				$tempmes = outputQueryInfo(1, "name", $lang_id, $app_id, 2, "qpkg_item_name", "ID: " . $row_ud['ID'], "Item_ID, Lang_ID, Content", "", "$app_id, $lang_id, $xml_item->name", "", 0);
			}
		}


		if (strlen($tempmes) > 0) {$message = $tempmes;}

		return $message;
	}

	function do_category($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
	{
		$message = "";
		$tempmes = "";

		if (strlen(getNodeName($xml_item->category)) == 0) {
                        $message = outputQueryInfo(2, "category", $lang_id, $app_id, 0, "", "", "", "", "", "No category tag.", 2);
                        return $message;
                }


		//if ($lang_id == 1) {
			$sql = "SELECT ID, Category_ID FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			$res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);
			$xml_cate_id = getDbCategoryId($connect, $xml_item->category);
			//echo $row['ID'] . $row['Category_ID'] . "$xml_cate_id" . "<br>";

			if ($xml_cate_id != $row['Category_ID']) {
				$sql_ud = "UPDATE qpkg_item SET Category_ID = '$xml_cate_id' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$tempmes = outputQueryInfo(1, "category", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Category_ID", $row['Category_ID'], $xml_cate_id, "", 0);

			}
		//} else {
			//
		//}


		if (strlen($tempmes) > 0) {$message = $tempmes;}

		return $message;
	}

        /*
         *
         */
        function do_type($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

		if (strlen(getNodeName($xml_item->type)) == 0) {
                        $message = outputQueryInfo(2, "type", $lang_id, $app_id, 0, "", "", "", "", "", "No type tag.", 2);
                        return $message;
                }


		//if ($lang_id == 1) {
			$sql = "SELECT * FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from qpkg_item table
			$res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);
			$xml_type_id = getDbTypeID($connect, $xml_item->type);			

			if ($xml_type_id != $row['Type_ID']) {
				$sql_ud = "UPDATE qpkg_item SET Type_ID = '$xml_type_id' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$tempmes = outputQueryInfo(1, "type", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Type_ID", $row['Type_ID'], $xml_type_id, "", 0);

			}
		//}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_icon80($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

 		if (strlen(getNodeName($xml_item->icon80)) == 0) {
                        $message = outputQueryInfo(2, "iocn80", $lang_id, $app_id, 0, "", "", "", "", "", "No icon80 tag.", 2);
                        return $message;
                }

                //if ($lang_id == 1) {
                        $sql = "SELECT ID, Icon80 FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from qpkg_item table
                        $res = mysqli_query($connect, $sql);
                        $row = mysqli_fetch_array($res);
			$xml_icon80 = substr(strrchr($xml_item->icon80, "/"), 1);

                        if (strcmp($xml_icon80, $row['Icon80']) != 0) {
                                $sql_ud = "UPDATE qpkg_item SET Icon80 = '$xml_icon80' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                                $res_ud = mysqli_query($connect, $sql_ud);
                                $tempmes = outputQueryInfo(1, "icon80", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Icon80", $row['Icon80'], $xml_icon80, "", 0);
                        }
                //}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_icon100($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

		if (strlen(getNodeName($xml_item->icon100)) == 0) {
                        $message = outputQueryInfo(2, "icon100", $lang_id, $app_id, 0, "", "", "", "", "", "No icon100 tag.", 2);
                        return $message;
                }
		

                //if ($lang_id == 1) {
                        $sql = "SELECT ID, Icon100 FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from qpkg_item table
                        $res = mysqli_query($connect, $sql);
                        $row = mysqli_fetch_array($res);
                        $xml_icon100 = substr(strrchr($xml_item->icon100, "/"), 1);

                        if (strcmp($xml_icon100, $row['Icon100']) != 0) {
                                $sql_ud = "UPDATE qpkg_item SET Icon100 = '$xml_icon100' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                                $res_ud = mysqli_query($connect, $sql_ud);
                                $tempmes = outputQueryInfo(1, "icon100", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Icon100", $row['Icon100'], $xml_icon100, "", 0);
                        }
                //}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_version($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

		if (strlen(getNodeName($xml_item->version)) == 0) {
			$message = outputQueryInfo(2, "version", $lang_id, $app_id, 0, "", "", "", "", "", "No version tag.", 2);
                        return $message;		
		}


		if ($lang_id == 1) {
			$sql = "SELECT ID, Version FROM qpkg_item WHERE ID = $app_id AND Project_ID = '$proj_id'";
			$res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);

			$db_version = $row['Version'];
			$xml_version = $xml_item->version;

			if (strcmp($db_version, $xml_version) != 0) {
				$sql_ud = "UPDATE qpkg_item SET Version = '$xml_version' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
                                $tempmes = outputQueryInfo(1, "version", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Version", $db_version, $xml_version, "", 0);
			}
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_fwVersion($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->fwVersion)) == 0) {
                //        $message = outputQueryInfo(2, "fwVersion", $lang_id, $app_id, 0, "", "", "", "", "", "No fwVersion tag.", 2);
                        return $message;
                }


                if ($lang_id == 1) {
                        $sql = "SELECT ID, fwVersion FROM qpkg_item WHERE ID = $app_id AND Project_ID = '$proj_id'";
                        $res = mysqli_query($connect, $sql);
                        $row = mysqli_fetch_array($res);

                        $db_fwVersion = $row['fwVersion'];
                        $xml_fwVersion = $xml_item->fwVersion;

                        if (strcmp($db_fwVersion, $xml_fwVersion) != 0) {
                                $sql_ud = "UPDATE qpkg_item SET fwVersion = '$xml_fwVersion' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                                $res_ud = mysqli_query($connect, $sql_ud);
                                $tempmes = outputQueryInfo(1, "fwVersion", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "fwVersion", $db_fwVersion, $xml_fwVersion, "", 0);
                        }
                }


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_snapshot($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->snapshot)) == 0) {
                //        $message = outputQueryInfo(2, "fwVersion", $lang_id, $app_id, 0, "", "", "", "", "", "No fwVersion tag.", 2);
                        return $message;
                }


		$sql = "SELECT ID, Snapshot FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
		$res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);

		$xml_snapshot = substr(strrchr($xml_item->snapshot, "/"), 1);
		$db_snapshot_state = $row['Snapshot'];


		if ($db_snapshot_state == 1) {  // have snapshot image
			$db_find_flag = true;
			$sql = "SELECT ID, Item_ID, Img_Name, Status FROM qpkg_item_snapshot WHERE Item_ID = '$app_id'";
                        $res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);
		
	
			if (strcmp($xml_snapshot, $row['Img_Name']) != 0) {
				$sql_hd = "UPDATE qpkg_item_snapshot SET Img_Name = '$xml_snapshot' WHERE Item_ID = '$app_id'";
				$res_hd = mysqli_query($connect, $sql_hd);
                                $tempmes = outputQueryInfo(1, "snapshot", $lang_id, $app_id, 1, "qpkg_item_snapshot", "ID: " . $row['ID'], "Img_Name", $row['Img_Name'], $xml_snapshot, "", 0);
			}	
		} else {
			// modify 'qpkg_item' table
			$sql_ud = "UPDATE qpkg_item SET Snapshot = '1' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			$res_ud = mysqli_query($connect, $sql_ud);
                        $tempmes = outputQueryInfo(1, "snapshot", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Snapshot", $row['Snapshot'], 1, "", 0);
			$sql_ud = "SELECT ID, Item_ID, Img_Name, Status FROM qpkg_item_snapshot WHERE Item_ID = '$app_id'";
			$res_ud = mysqli_query($connect, $sql_ud);
			$row_ud = mysqli_fetch_array($res_ud);


			if (mysqli_num_rows($res_ud) == 0) {  // not found data. insert data.
				$sql_ud = "INSERT INTO qpkg_item_snapshot (Item_ID, Img_Name, Status) VALUES ('$app_id', '$xml_snapshot', '1')";
				$res_ud = mysqli_query($connect, $sql_ud);
				$tempmes = outputQueryInfo(1, "snapshot", $lang_id, $app_id, 2, "qpkg_item_snapshot", "ID: " . $row['ID'], "Item_ID, Img_Name, Status", "", $app_id . $xml_snapshot . "1", "", 0);
			} else {  // update
				if (strcmp($row_ud['Img_Name'], $xml_snapshot) != 0) {
					$sql_ud = "UPDATE qpkg_item_snapshot SET Img_Name = '$xml_snapshot' WHERE Item_ID = '$app_id'";
					$res_ud = mysqli_query($connect, $sql_ud);
					$tempmes = outputQueryInfo(1, "snapshot", $lang_id, $app_id, 1, "qpkg_item_snapshot", "ID: " . $row['ID'], "Img_Name", $row['Img_Name'], $xml_snapshot, "", 0);
				}
			}
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_snapshotSmall($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";
                
		if (strlen(getNodeName($xml_item->snapshotSmall)) == 0) {
                //        $message = outputQueryInfo(2, "fwVersion", $lang_id, $app_id, 0, "", "", "", "", "", "No fwVersion tag.", 2);
                        return $message;
                }


		$sql = "SELECT ID, Snapshot_Small FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
		$res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);

		$xml_ss = $xml_item->snapshotSmall;
		$db_ss = $row['Snapshot_Small'];


		if (strcmp($xml_ss, $db_ss) != 0) {
			$sql_ud = "UPDATE qpkg_item SET Snapshot_Small = '$xml_ss' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			mysqli_query($connect, $sql_ud);
			$tempmes = outputQueryInfo(1, "snapshotSmall", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Snapshot_Small", $db_ss, $xml_ss, "", 0);
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_publishedDate($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->publishedDate)) == 0) {
                        $message = outputQueryInfo(2, "publishedDate", $lang_id, $app_id, 0, "", "", "", "", "", "No publishedDate tag.", 2);
                        return $message;
                }

		
		$sql = "SELECT ID, Publish_Date FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
		$res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);

		if (strcmp($row['Publish_Date'], $xml_item->publishedDate) != 0) {
			$sql_ud = "UPDATE qpkg_item SET Publish_Date = '$xml_item->publishedDate' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                        mysqli_query($connect, $sql_ud);
                        $tempmes = outputQueryInfo(1, "publishedDate", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Publish_Date", $row['Publish_Date'], $xml_item->publishedDate, "", 0);
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}


                return $message;
        }

        /*
         *
         */
        function do_maintainer($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->maintainer)) == 0) {
                        //$message = outputQueryInfo(2, "maintainer", $lang_id, $app_id, 0, "", "", "", "", "", "No maintainer tag.", 2);
                        return $message;
                }


		$sql = "SELECT ID, Maintainer FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from 'qpkg_item' table
                $res = mysqli_query($connect, $sql);
		$row = mysqli_fetch_array($res);

		if (strcmp($row['Maintainer'], $xml_item->maintainer) != 0) {
			$sql_ud = "UPDATE qpkg_item SET Maintainer = '$xml_item->maintainer' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			$res_ud = mysqli_query($connect, $sql_ud);
			$tempmes = outputQueryInfo(1, "maintainer", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Maintainer", $row['Maintainer'], $xml_item->maintainer, "", 0);
		}	


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
	 *
	 */
        function do_developer($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->developer)) == 0) {
                        //$message = outputQueryInfo(2, "developer", $lang_id, $app_id, 0, "", "", "", "", "", "No developer tag.", 2);
                        return $message;
                }


                $sql = "SELECT ID, Developer FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from 'qpkg_item' table
                $res = mysqli_query($connect, $sql);
                $row = mysqli_fetch_array($res);

                if (strcmp($row['Developer'], $xml_item->developer) != 0) {
                        $sql_ud = "UPDATE qpkg_item SET Developer = '$xml_item->developer' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                        $res_ud = mysqli_query($connect, $sql_ud);
                        $tempmes = outputQueryInfo(1, "developer", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Developer", $row['Developer'], $xml_item->developer, "", 0);
                }


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
	 *
	 */
        function do_developerLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->developerLink)) == 0) {
                        //$message = outputQueryInfo(2, "developerLink", $lang_id, $app_id, 0, "", "", "", "", "", "No developerLink tag.", 2);
                        return $message;
                }


                $sql = "SELECT ID, Developer_Link FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from 'qpkg_item' table
                $res = mysqli_query($connect, $sql);
                $row = mysqli_fetch_array($res);

                if (strcmp($row['Developer_Link'], $xml_item->developerLink) != 0) {
                        $sql_ud = "UPDATE qpkg_item SET Developer_Link = '$xml_item->developerLink' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                        $res_ud = mysqli_query($connect, $sql_ud);
                        $tempmes = outputQueryInfo(1, "developerLink", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Developer_Link", $row['Developer_Link'], $xml_item->developerLink, "", 0);
                }


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
	 *
	 */
        function do_forumLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->forumLink)) == 0) {
                        //$message = outputQueryInfo(2, "forumLink", $lang_id, $app_id, 0, "", "", "", "", "", "No forumLink tag.", 2);
                        return $message;
                }

		
		if ($lang_id == 1) {
                	$sql = "SELECT ID, Forum_Link FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from 'qpkg_item' table
                	$res = mysqli_query($connect, $sql);
                	$row = mysqli_fetch_array($res);


	                if (strcmp($row['Forum_Link'], $xml_item->forumLink) != 0) {
        	                $sql_ud = "UPDATE qpkg_item SET Forum_Link = '$xml_item->forumLink' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
        	                $res_ud = mysqli_query($connect, $sql_ud);
        	                $tempmes = outputQueryInfo(1, "forumLink", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Forum_Link", $row['Forum_Link'], $xml_item->forumLink, "", 0);
        	        }
		} else {
			$sql = "SELECT ID, Item_ID, URL FROM qpkg_item_url WHERE Item_id = '$app_id' AND Lang_ID = '$lang_id' AND Type = '1'";
			$res = mysqli_query($connect, $sql);

			if (mysqli_num_rows($res) > 0) {
				while ($row = mysqli_fetch_array($res)) {
					if (strcmp($row['URL'], $xml_item->forumLink) != 0) {
						$sql_ud = "UPDATE qpkg_item_url SET URL = '$xml_item->forumLink' WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '1'";
						$res_ud = mysqli_query($connect, $sql_ud);
						$tempmes = outputQueryInfo(1, "forumLink", $lang_id, $app_id, 1, "qpkg_item_url", "ID: " . $row['ID'], "URL", $row['URL'], $xml_item->forumLink, "", 0);
					}
				}
			} else {
				$sql_ud = "INSERT INTO qpkg_item_url SET Item_ID = '$app_id', Lang_ID = '$lang_id', Type = '1', URL = '$xml_item->forumLink'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$sql_ud = "SELECT ID, URL FROM qpkg_item_url WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '1'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);
				$tempmes = outputQueryInfo(1, "forumLink", $lang_id, $app_id, 2, "qpkg_item_url", "ID: " . $row_ud['ID'], "URL", "", $xml_item->forumLink, "", 0);
			}
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
	 *
	 */
        function do_wikiLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->wikiLink)) == 0) {
                        //$message = outputQueryInfo(2, "wikiLink", $lang_id, $app_id, 0, "", "", "", "", "", "No wikiLink tag.", 2);
                        return $message;
                }
          
         
		if ($lang_id == 1) { 
                	$sql = "SELECT ID, Wiki_Link FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from 'qpkg_item' table
	                $res = mysqli_query($connect, $sql);
        	        $row = mysqli_fetch_array($res);
	
        	        if (strcmp($row['Wiki_Link'], $xml_item->wikiLink) != 0) {
                	        $sql_ud = "UPDATE qpkg_item SET Wiki_Link = '$xml_item->wikiLink' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
                	        $res_ud = mysqli_query($connect, $sql_ud);
                	        $tempmes = outputQueryInfo(1, "wikiLink", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Wiki_Link", $row['Wiki_Link'], $xml_item->wikiLink, "", 0);
                	}
		} else {
			$sql = "SELECT ID, Item_ID, URL FROM qpkg_item_url WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '2'";
			$res = mysqli_query($connect, $sql);

			if (mysqli_num_rows($res) > 0) {
				while ($row = mysqli_fetch_array($res)) {
					//echo "DEBUG: $app_id, $lang_id, " . $row['URL'] . ", " . $xml_item->wikiLink . "<br>";
					if (strcmp($row['URL'], $xml_item->wikiLink) != 0) {
						$sql_ud = "UPDATE qpkg_item_url SET URL = '$xml_item->wikiLink' WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '2'";
						$res_ud = mysqli_query($connect, $sql_ud);
						$tempmes = outputQueryInfo(1, "wikiLink", $lang_id, $app_id, 1, "qpkg_item_url", "ID: " . $row['ID'], "URL", $row['URL'], $xml_item->wikiLink, "", 0);
					}
				}
			} else {
				//echo "DEBUG: $app_id, $lang_id, " . $row['URL'] . ", " . $xml_item->wikiLink . "<br>";
				$sql_ud = "INSERT INTO qpkg_item_url SET Item_ID = '$app_id', Lang_ID = '$lang_id', Type = '2', URL = '$xml_item->wikiLink'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$sql_ud = "SELECT ID, URL FROM qpkg_item_url WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '2'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);
				$tempmes = outputQueryInfo(1, "wikiLink", $lang_id, $app_id, 2, "qpkg_item_url", "ID: " . $row_ud['ID'], "URL", "", $xml_item->wikiLink, "", 0);
			}
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
	 *
	 */
        function do_tutorialLink($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->tutorialLink)) == 0) {
                        //$message = outputQueryInfo(2, "tutorialLink", $lang_id, $app_id, 0, "", "", "", "", "", "No tutorialLink tag.", 2);
                        return $message;
                }

		
		//  check tutorial file type
		$file_name = "";
		$file_ext = substr(strrchr($xml_item->tutorialLink, '.'), 1);
		$file_status = 0;
		switch ($file_ext) {
			case "pdf": // file
				$file_status = 2;
				$file_name = substr(strrchr($xml_item->tutorialLink, '/'), 1);
				break;
			case "rar":  // file
				$file_status = 2;
				$file_name = substr(strrchr($xml_item->tutorialLink, '/'), 1);
				break;
			case "zip":  // file
				$file_status = 2;
				$file_name = substr(strrchr($xml_item->tutorialLink, '.'), 1);
				break;
			default:  // link
				$file_status = 1;
				break;
		}
		//echo $app_id . ", " . $file_ext . ", " . $file_name . "_get<br>";


		//  query database
                $sql = "SELECT ID, Tutorial_Type, Tutorial_Link FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";  // get data from 'qpkg_item' table
                $res = mysqli_query($connect, $sql);
                $row = mysqli_fetch_array($res);


                if ($file_status == 0) {
			if ($lang_id == 1) {
				if ($row['Tutorial_Type'] != 0) {
					$sql_ud = "UPDATE qpkg_item SET Tutorial_Type = '0' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
					$res_ud = mysqli_query($connect, $sql_ud);
					$tempmes = outputQueryInfo(1, "tutorialLink", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Tutorial_Type", $row['Tutorial_Type'] , "0", "", 0);
				}
			}
		} else if ($file_status == 1) {
			//echo "DEBUG: $app_id, $lang_id, $xml_item->tutorialLink";
			if ($lang_id == 1) {
				if ($row['Tutorial_Type'] != 1 || strcmp($row['Tutorial_Link'], $xml_item->tutorialLink) != 0) {
					$sql_ud = "UPDATE qpkg_item SET Tutorial_Type = '1', Tutorial_Link = '$xml_item->tutorialLink' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
					$res_ud = mysqli_query($connect, $sql_ud);
					$tempmes = outputQueryInfo(1, "tutorialLink", $lang_id, $app_id, 1, "qpkg_item", "ID: " . $row['ID'], "Tutorial_Type, Tutorial_Link", $row['Tutorial_Type'] . ", " . $row['Tutorial_Link'], "1, $xml_item->tutorialLink", "", 0);
				}
			} else {
				$sql_ud = "SELECT ID, URL FROM qpkg_item_url WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '3'";
				$res_ud = mysqli_query($connect, $sql_ud);

				if (mysqli_num_rows($res_ud) > 0) {
					$row_ud = mysqli_fetch_array($res_ud);
					if (strcmp($row_ud['URL'], $xml_item->tutorialLink) != 0) {
						$sql_ud2 = "UPDATE qpkg_item_url SET URL = '$xml_item->tutorialLink' WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '3'";
						$res_ud2 = mysqli_query($sql_ud2);
						$tempmes = outputQueryInfo(1, "tutorialLink", $lang_id, $app_id, 1, "qpkg_item_url", "ID: " . $row_ud['ID'], "URL", $row_ud['URL'], $xml_item->tutorialLink, "", 0);	
					}
				} else {
					$sql_ud2 = "INSERT INTO qpkg_item_url (Item_ID, Lang_ID, Type, URL) VALUES ('$app_id', '$lang_id', '3', '$xml_item->tutorialLink')";
					$res_ud2 = mysqli_query($connect, $sql_ud2);
					$sql_ud2 = "SELECT ID FROM qpkg_item_url WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id' AND Type = '3'";
					$res_ud2 = mysqli_query($connect, $sql_ud2);
					$tempmes = outputQueryInfo(1, "tutorialLink", $lang_id, $app_id, 2, "qpkg_item_url", "ID: " . $row_ud2['ID'], "URL", "", $xml_item->tutorialLink, "", 0);
				}
			}
		} else if ($file_status == 2) {
			if ($row['Tutorial_Type'] != 2) {
				$sql_ud = "UPDATE qpkg_item SET Tutorial_Type = '1' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
				$res_ud = mysqli_query($connect, $sql_ud);

				// search 'qpkg_item_tutorial' table
				$sql_ud = "SELECT ID, Item_ID, Data_Name, Status FROM qpkg_item_tutorial WHERE Item_ID = '$app_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);
				if (mysqli_num_rows($res_ud) > 0) {  // have data
					if (strcmp($row_ud['Data_Name'], $file_name) != 0) {
						$sql_ud = "UPDATE qpkg_item_tutorial SET Data_Name = '$file_name' WHERE Item_ID = '$app_id'";
						$res_ud = mysqli_query($connect, $sql_ud);
						$tempmes = outputQueryInfo(1, "tutorialLink", $lang_id, $app_id, 1, "qpkg_item_tutorial", "ID: " . $row['ID'], "Data_Name", $row_ud['Data_Name'], $file_name, "", 0);
					}
				} else {  // no data. insert new data
					$sql_ud = "INSERT INTO qpkg_item_tutorial (Item_ID, Data_Name, Status) VALUES ('$app_id', '$file_name', '1')";
					$res_ud = mysqli_query($connect, $sql_ud);
					$tempmes = outputQueryInfo(1, "tutorialLink", $lang_id, $app_id, 2, "qpkg_item_tutorial", "ID: ", "Item_ID, Data_Name Status", "NULL", "$app_id, $file_name, 1", "", 0);
				}
			}
		} else {
			//
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

        /*
         *
         */
        function do_platform($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->platform)) == 0) {
                //        $message = outputQueryInfo(2, "platform", $lang_id, $app_id, 0, "", "", "", "", "", "No platform tag.", 2);
                        return $message;
                }

		// pass Virtualization Station. internal name is "QKVM".  // -----***
		if (strcmp($xml_item->internalName, "QKVM") == 0) {
			$message = outputQueryInfo(2, "platform", $lang_id, $app_id, 0, "", "", "", "", "", "Pass platform tag of Virtualization Station!!!", 2);
			return $message;
		}


		foreach ($xml_item->platform as $sub_platform) {
			$pl_id = getDbPlatID($connect, $sub_platform->platformID);  // id
			$pl_ex = getDbPlatExID($connect, $sub_platform->platformExcl);  // id
			$pl_loc = substr(strrchr($sub_platform->location, "/"), 1);  // file name

			// only process data that Type = 1
			$sql = "SELECT ID, Item_ID, Platform_ID, Platform_Excl, Data_Name FROM qpkg_item_platform WHERE Item_ID = '$app_id' AND Type = '1' AND Platform_ID = '$pl_id'";
			$res = mysqli_query($connect, $sql);

			if (mysqli_num_rows($res) > 0) {  // update DB
				while ($row = mysqli_fetch_array($res)) {
					$db_pl_id = $row['Platform_ID'];
					$db_pl_ex = $row['Platform_Excl'];
					$db_pl_loc = $row['Data_Name'];

					// check "Platform_Excl"
					if ($pl_ex != $db_pl_ex) {
						$sql_ud = "";
						if (empty($pl_ex) == true) {  // null
							$sql_ud = "UPDATE qpkg_item_platform SET Platform_Excl = NULL WHERE ID = '" . $row['ID'] . "'";
						} else {
							$sql_ud = "UPDATE qpkg_item_platform SET Platform_Excl = '$pl_ex' WHERE ID = ' " . $row['ID'] . " '";
						}

						$res_ud = mysqli_query($connect, $sql_ud);
						$tempmes = outputQueryInfo(1, "platform", $lang_id, $app_id, 1, "qpkg_item_platform", "ID: " . $row['ID'], "Platform_Excl", $db_pl_ex, $pl_ex, "", 0);
					}

					// check "Data_Name"
					if (strcmp($pl_loc, $db_pl_loc) != 0) {
						$sql_ud = "UPDATE qpkg_item_platform SET Data_Name = '$pl_loc' WHERE ID = '" . $row['ID'] . "'";
						$res_ud = mysqli_query($connect, $sql_ud);
						$tempmes = outputQueryInfo(1, "platform", $lang_id, $app_id, 1, "qpkg_item_platform", "ID: " . $row['ID'], "Data_Name", $db_pl_loc, $pl_loc, "", 0);
					}
				}
			} else {  // insert new data into DB
				$sql_ud = "";
				if (empty($pl_ex) == true) {
					$sql_ud = "INSERT INTO qpkg_item_platform (Item_ID, Type, Platform_ID, Platform_Excl, Data_Name, Status) VALUES ('$app_id', '1', '$pl_id', NULL, '$pl_loc', '1')";
				} else {
					$sql_ud = "INSERT INTO qpkg_item_platform (Item_ID, Type, Platform_ID, Platform_Excl, Data_Name, Status) VALUES ('$app_id', '1', '$pl_id', '$pl_ex', '$pl_loc', '1')";
				}

				$res_ud = mysqli_query($connect, $sql_ud);
				$sql_ud = "SELECT ID FROM qpkg_item_platform WHERE Item_ID = '$app_id' AND Type = '1' AND Platform_ID = '$pl_id' ";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);
				$tempmes = outputQueryInfo(1, "platform", $lang_id, $app_id, 2, "qpkg_item_platform", "ID: " . $row_ud['ID'], "Item_ID, Type, Platform_ID, Platform_Excl, Data_Name, Status", "", "$app_id, $pl_id, $pl_ex, $pl_loc, 1", "", 0);
			}
		}


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }

	/*
         *
         */
        function do_description($xml_item, $app_id, $proj_id, $lang_id, $type, $connect)
        {
                $message = "";
                $tempmes = "";

                if (strlen(getNodeName($xml_item->description)) == 0) {
                //        $message = outputQueryInfo(2, "description", $lang_id, $app_id, 0, "", "", "", "", "", "No descriptin tag.", 2);
                        return $message;
                }


		if ($lang_id == 1) {
			$sql = "SELECT Description FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			$res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);
		
	
			if (strcmp($row['Description'], $xml_item->description) != 0) {
				$tc = mysqli_real_escape_string($connect, $xml_item->description);
				$sql_ud = "UPDATE qpkg_item SET Description = '$tc' WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$tempmes = outputQueryInfo(1, "description", $lang_id, $app_id, 1, "qpkg_item", "ID: $app_id", "Description", $row['Description'], $xml_item->description, "", 0);
			}
		} else {
			//  check qpkg_item_language table and qpkg_item_description table

			$sql = "SELECT Description FROM qpkg_item WHERE ID = '$app_id' AND Project_ID = '$proj_id'";
			$res = mysqli_query($connect, $sql);
			$row = mysqli_fetch_array($res);

			if (strcmp($row['Description'], $xml_item->description) != 0) {  // compare with eng description
				$sql_ud = "SELECT ID, Status FROM qpkg_item_language WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);
				
				if ($row_ud['Status'] == 2) {
					$sql_ud2 = "UPDATE qpkg_item_language SET Status = '1' WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
					$res_ud2 = mysqli_query($connect, $sql_ud2);
					$tempmes = outputQueryInfo(1, "description", $lang_id, $app_id, 1, "qpkg_item_language", "ID: " . $row_ud['ID'], "Status", 2, 1, "", 0);
				}

				$sql_ud = "SELECT ID, Content FROM qpkg_item_description WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
				$res_ud = mysqli_query($connect, $sql_ud);
				$row_ud = mysqli_fetch_array($res_ud);

				if (mysqli_num_rows($res_ud) > 0) {
					if (strcmp($row_ud['Content'], $xml_item->description) != 0) {
						$tcc = $xml_item->description;
						$tcc = mysqli_real_escape_string($connect, $tcc);
						$tid = $row_ud['ID'];
						$sql_uda = "UPDATE qpkg_item_description SET Content = '$tcc' WHERE ID = '$tid'";
						$res_uda = mysqli_query($connect, $sql_uda);
						$tempmes = outputQueryInfo(1, "description", $lang_id, $app_id, 1, "qpkg_item_description", "ID: " . $row_ud['ID'], "Content", $row_ud['Content'], $xml_item->description, "", 0);
					}
				} else {
					$tc = (string) $xml_item->description;
					$tc = mysqli_real_escape_string($connect, $tc);
					$sql_udb = "INSERT INTO qpkg_item_description (Item_ID, Lang_ID, Content) VALUES ('$app_id', '$lang_id', '$tc')";
					$res_udb = mysqli_query($connect, $sql_udb);
					
					$sql_udc = "SELECT ID FROM qpkg_item_description WHERE Item_ID = '$app_id' AND Lang_ID = '$lang_id'";
					$res_udc = mysqli_query($connect, $sql_udc);
					$row_udc = mysqli_fetch_array($res_udc);
					$res_id = $row_udc['ID'];
					$tempmes = outputQueryInfo(1, "description", $lang_id, $app_id, 2, "qpkg_item_description", "ID: $res_id", "Content", $row_ud['Content'], $xml_item->description, "", 0);
				}
			}
		}	


                if (strlen($tempmes) > 0) {$message = $tempmes;}

                return $message;
        }
?>
