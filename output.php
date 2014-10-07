<?php
/*
 * output.php
 *
 * wrap system information with html tags
 */


	/*
	 * add html tag
	 */
	function output_info($data)
	{
		$result = "";
		$temp = "";
		
		foreach ($data as $d)
		{
			$temp .= "<div>$d</div>";
		}

		$result = "<div>$temp</div>";

		return $result;
	}


	/*
	 * add html tag
	 */
	function transFinfoToHtml($valid, $error, $name, $type, $size)
	{
		$result = "";

		if ($valid == 0) {
			$result = "<div>no file found.</div>";
		} else if ($valid == 1) {
			$result = "<div>$name: upload file finished.</div>";
		} else if ($valid == 2) {
			$result = "<div>$name: file exist.</div>";
		} else if ($valid == -1) {
			$result = "<div>$name: Invalid file.</div>";
		} else {
			//
		}

		return $result;		
	}

	/*
	 * generate query information
	 *
	 * @type: 0 => normal, 1 => DB, 2=> xml
	 * @tag: xml tag
	 * @$db_action: 0 => SELECT, 1 => UPDATE, 2 => INSERT, 3 => DELETE
	 * @status: 0 = >OK, 1 => warning, 2 => error
	 * @return: html format string
	 */
	function outputQueryInfo($type, $tag, $file, $app_id, $db_action, $db_table, $db_row, $db_field, $db_value1, $db_value2, $ex, $status)
	{
		$result = "";

		$t_db_a = "";
		$t_s = "";

		if ($db_action == 0) {$t_db_a = "SELECT";}
		else if ($db_action == 1) {$t_db_a = "UPDATE";}
		else if ($db_action == 2) {$t_db_a = "INSERT";}
		else if ($db_action == 3) {$t_db_a = "DELETE";}
		else if ($db_action == -1) {$t_db_a = "";}
		else {}

		if ($status == 0) {$t_s = "ok";}
		else if ($status == 1) {$t_s = "warning";}
		else if ($status == 2) {$t_s = "error";}
		else {}

		if ($type == 0) {

		} else if ($type == 1) {
			$result = "<div class='$t_s $tag'>* System info: [$tag][file: $file][app: $app_id][$t_s] Do $t_db_a at '$db_table' table '$db_row' row. Change ($db_field) field from ($db_value1) to ($db_value2). $ex</div>";
		} else if ($type == 2) {
			$result = "<div class='$t_s $tag'>* System info: [$tag][file: $file][app: $app_id][$t_s] $ex</div>";
		}

		return $result;
	}
?>
