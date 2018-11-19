<?php

include_once "./Modules/TestQuestionPool/classes/class.ilQuestionsPlugin.php";

/**
* Main plugin file of the SQLQuestion plugin
*
* @author Dominik Probst <dominik.probst@studium.fau.de>
* @version $Id$
* @ingroup ModulesTestQuestionPool
*/
class ilassSQLQuestionPlugin extends ilQuestionsPlugin
{
		final function getPluginName()
		{
			return "assSQLQuestion";
		}

		final function getQuestionType()
		{
			return "assSQLQuestion";
		}

		final function getQuestionTypeTranslation()
		{
			return $this->txt($this->getQuestionType());
		}
}
?>
