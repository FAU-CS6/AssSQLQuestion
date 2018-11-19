<?php

include_once "./Modules/TestQuestionPool/classes/export/qti12/class.assSQLQuestionExport.php";

/**
* SQL question export
*/
class assSQLQuestionExport extends assQuestionExport
{
	/**
	* Returns a QTI xml representation of the question
	*
	* @return string The QTI xml representation of the question
	* @access public
	*/
	function toXML($a_include_header = true, $a_include_binary = true, $a_shuffle = false, $test_output = false, $force_image_references = false)
	{
		//TODO
		return "null";
	}
}

?>
