<?php
/*
 * File: processXml.php
 *
 * process xml file
 *
 * Author: Alan Ho
 * Time: 2014/07/16
 */


	/*
	 * open directory
	 *
	 * @dir: directory path
	 * @return: file name in this directory.  array.
	 */
	function getFiles($path)
	{
		$array = array();

		if ($handle = opendir($path)) {
			while (false !== ($str = readdir($handle))) {
				if ($str != "." && $str != "..") {
					//echo "$str\n";
					array_push($array, $path . $str);
				}
			}
		}

		closedir($handle);
		return $array;
	}

	/*
	 * open xml files
	 * 
	 * @return xml file entry list.  array.
	 */
	function openFiles($file_name_list)
	{
		$array = array();
		foreach ($file_name_list as $item) {
			array_push($array, simplexml_load_file($item));
		}

		return $array;
	}

	/*
	 * open xml file
	 *
	 * @return xml file entry.
	 */
	function openFile($file_name)
	{
		return simplexml_load_file($file_name);
	}

	/*
	 * get node value
	 *
	 * @node: xml node entry
	 * @return: node value
	 */
	function getNodeValue($node)
	{
		return $node;
	}

	/*
	 * get node name
	 *
	 * @node: xml node entry
	 * @return: node name
	 */
	function getNodeName($node)
	{
		return $node->getName();
	}

	/*
	 * get next level node value list of current node
	 *
	 * @node: xml node entry.
	 * @return: child list. array.
	 */
	function getChildValueList($node)
	{
		$array = array();
		foreach ($node->children() as $item) {
			array_push($array, $item);
		}
		
		return $array;
	}

	/*
	 * get next level node name list of current node
	 *
	 * @node: xml node entry.
	 * @return: tag list. array.
	 */
	function getChildNameList($node)
	{
		$array = array();
		foreach ($node->children() as $item) {
			array_push($array, $item->getName());
		}

		return $array;
	}

	/*
	 * get xml language type
	 *
	 * @xml_name: xml file name
	 * @return: xml language
	 */
	function xmlLang($type, $xml_name)
	{
		$temp = explode('/', $xml_name);
		$temp = $temp[1];
		$temp = explode('_', $temp);
		$temp = $temp[1];
		$temp = explode('.', $temp);
		$temp = $temp[0];

		return $temp;
	}

	/*
	 * check xml structure and print wrong part.
	 * this template is only for AppCenter XML files.
	 */
	function checkXmlStructure()
	{

		// how to find error part of xml structure?
		//
		// coding later
		//

		$result = "";

		/* opne file and get xml entry */
		include 'setting.php';
		$xml_path = getFilePath();
		$file_name_list = getFile($xml_path);
		$xml_entry = openFile($file_name_list);


		/* start check */

		$xml_file_number = count($xml_entry);
		
		for ($count = 0; $count < $xml_file_number; $count++) {
			$node = $xml_entry[$count];
			if ($node === false) {
				//echo "Failed loading XML\n";
				foreach(libxml_get_errors() as $error) {
					//echo "\t", $error->message;
				}
			}

			$str = (string) getNodeValue($node);
			$temp = "1" . $str . "2";
			//echo $temp;
			$a = strlen($temp) . "_";
			//echo $a;
			//echo "<br>";

			/* wrapper information */
		
		}		
	}

	/*
	 * print xml structure
	 *
	 * @xml_entry: xml entry
	 * $return: structure string
	 */
	function xmlStructure($xml_entry)
	{
		$result = "";

		foreach ($xml_entry->item as $node) {
			foreach ($node->children() as $node2) {
				if (strcmp(getNodeName($node2), platform) != 0) {
					$result .= getNodeName($node2) . ": " . getNodeValue($node2) . "<br>";
				} else {
					$result .= "platform:<br>"
						. "<blockquote>" . getNodeName($node2->platformID) . ": " . getNodeValue($node2->platformID) . "</blockquote>"
						. "<blockquote>" . getNodeName($node2->location) . ": " . getNodeValue($node2->location) . "</blockquote>";
				}
			}
			$result .= "<hr>";
		}

		return $result;
	}

	/*
	 * just a test message
	 */
	function test()
	{
		return "in test function";
	}
?>
