<?php

require_once "./Modules/TestQuestionPool/classes/class.assQuestion.php";

/**
 * Main defintion of the SQLQuestion plugin
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @ingroup ModulesTestQuestionPool
 */
class assSQLQuestion extends assQuestion
{
	/**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
	protected $plugin = null;


	/**
	 * Constructor
	 *
	 * The constructor takes possible arguments and creates an instance of the question object.
	 *
	 * @param string $title A title string to describe the question
	 * @param string $comment A comment string to describe the question
	 * @param string $author A string containing the name of the questions author
	 * @param integer $owner A numerical ID to identify the owner/creator
	 * @param string $question Question text
	 * @access public
	 *
	 * @see assQuestion:assQuestion()
	 */
	function __construct(
		$title = '',
		$comment = '',
		$author = '',
		$owner = -1,
		$question = ''
	)
	{
		// needed for excel export
		$this->getPlugin()->loadLanguageModule();

		parent::__construct($title, $comment, $author, $owner, $question);
	}

	/**
	 * Returns the question type of the question
	 *
	 * @return string The question type of the question
	 */
	public function getQuestionType()
	{
		return 'assSQLQuestion';
	}

	/**
	 * Returns the names of the additional question data tables
	 *
	 * All tables must have a 'question_fi' column.
	 * Data from these tables will be deleted if a question is deleted
	 *
	 * @return mixed 	the name(s) of the additional tables (array or string)
	 */
	public function getAdditionalTableName()
	{
		return '';
	}

	/**
	 * Collects all texts in the question which could contain media objects
	 * which were created with the Rich Text Editor
	 */
	protected function getRTETextWithMediaObjects()
	{
		$text = parent::getRTETextWithMediaObjects();

		// eventually add the content of question type specific text fields
		// ..

		return $text;
	}


	/**
	 * Get the plugin object
	 *
	 * @return object The plugin object
	 */
	public function getPlugin()
	{
		if ($this->plugin == null)
		{
			$this->plugin = ilPlugin::getPluginObject(IL_COMP_MODULE, 'TestQuestionPool', 'qst', 'assSQLQuestion');

		}
		return $this->plugin;
	}

	/**
	 * Returns true, if the question is complete
	 *
	 * @return boolean True, if the question is complete for use, otherwise false
	 */
	public function isComplete()
	{
		// Please add here your own check for question completeness
		// The parent function will always return false
		if(!empty($this->title) && !empty($this->author) && !empty($this->question) && $this->getMaximumPoints() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Saves a question object to a database
	 *
	 * @param	string		$original_id
	 * @access 	public
	 * @see assQuestion::saveToDb()
	 */
	function saveToDb($original_id = '')
	{

		// save the basic data (implemented in parent)
		// a new question is created if the id is -1
		// afterwards the new id is set
		$this->saveQuestionDataToDb($original_id);

		// Now you can save additional data
		// ...

		// save stuff like suggested solutions
		// update the question time stamp and completion status
		parent::saveToDb();
	}

	/**
	 * Loads a question object from a database
	 * This has to be done here (assQuestion does not load the basic data)!
	 *
	 * @param integer $question_id A unique key which defines the question in the database
	 * @see assQuestion::loadFromDb()
	 */
	public function loadFromDb($question_id)
	{
		global $DIC;
		$ilDB = $DIC->database();

		// load the basic question data
		$result = $ilDB->query("SELECT qpl_questions.* FROM qpl_questions WHERE question_id = "
				. $ilDB->quote($question_id, 'integer'));

		$data = $ilDB->fetchAssoc($result);
		$this->setId($question_id);
		$this->setObjId($data['obj_fi']);
		$this->setOriginalId($data['original_id']);
		$this->setOwner($data['owner']);
		$this->setTitle($data['title']);
		$this->setAuthor($data['author']);
		$this->setPoints($data['points']);
		$this->setComment($data['description']);
		$this->setSuggestedSolution($data['solution_hint']);

		$this->setQuestion(ilRTE::_replaceMediaObjectImageSrc($data['question_text'], 1));
		$this->setEstimatedWorkingTime(substr($data['working_time'], 0, 2), substr($data['working_time'], 3, 2), substr($data['working_time'], 6, 2));

		// now you can load additional data
		// ...

		try
		{
			$this->setAdditionalContentEditingMode($data['add_cont_edit_mode']);
		}
		catch(ilTestQuestionPoolException $e)
		{
		}

		// loads additional stuff like suggested solutions
		parent::loadFromDb($question_id);
	}


	/**
	 * Duplicates a question
	 * This is used for copying a question to a test
	 *
	 * @param bool   		$for_test
	 * @param string 		$title
	 * @param string 		$author
	 * @param string 		$owner
	 * @param integer|null	$testObjId
	 *
	 * @return void|integer Id of the clone or nothing.
	 */
	public function duplicate($for_test = true, $title = '', $author = '', $owner = '', $testObjId = null)
	{
		if ($this->getId() <= 0)
		{
			// The question has not been saved. It cannot be duplicated
			return;
		}

		// make a real clone to keep the actual object unchanged
		$clone = clone $this;

		$original_id = assQuestion::_getOriginalId($this->getId());
		$clone->setId(-1);

		if( (int) $testObjId > 0 )
		{
			$clone->setObjId($testObjId);
		}

		if (!empty($title))
		{
			$clone->setTitle($title);
		}
		if (!empty($author))
		{
			$clone->setAuthor($author);
		}
		if (!empty($owner))
		{
			$clone->setOwner($owner);
		}

		if ($for_test)
		{
			$clone->saveToDb($original_id);
		}
		else
		{
			$clone->saveToDb();
		}

		// copy question page content
		$clone->copyPageOfQuestion($this->getId());
		// copy XHTML media objects
		$clone->copyXHTMLMediaObjectsOfQuestion($this->getId());

		// call the event handler for duplication
		$clone->onDuplicate($this->getObjId(), $this->getId(), $clone->getObjId(), $clone->getId());

		return $clone->getId();
	}

	/**
	 * Copies a question
	 * This is used when a question is copied on a question pool
	 *
	 * @param integer	$target_questionpool_id
	 * @param string	$title
	 *
	 * @return void|integer Id of the clone or nothing.
	 */
	function copyObject($target_questionpool_id, $title = '')
	{
		if ($this->getId() <= 0)
		{
			// The question has not been saved. It cannot be duplicated
			return;
		}

		// make a real clone to keep the object unchanged
		$clone = clone $this;

		$original_id = assQuestion::_getOriginalId($this->getId());
		$source_questionpool_id = $this->getObjId();
		$clone->setId(-1);
		$clone->setObjId($target_questionpool_id);
		if (!empty($title))
		{
			$clone->setTitle($title);
		}

		// save the clone data
		$clone->saveToDb();

		// copy question page content
		$clone->copyPageOfQuestion($original_id);
		// copy XHTML media objects
		$clone->copyXHTMLMediaObjectsOfQuestion($original_id);

		// call the event handler for copy
		$clone->onCopy($source_questionpool_id, $original_id, $clone->getObjId(), $clone->getId());

		return $clone->getId();
	}

	/**
	 * Create a new original question in a question pool for a test question
	 * @param int $targetParentId			id of the target question pool
	 * @param string $targetQuestionTitle
	 * @return int|void
	 */
	public function createNewOriginalFromThisDuplicate($targetParentId, $targetQuestionTitle = '')
	{
		if ($this->id <= 0)
		{
			// The question has not been saved. It cannot be duplicated
			return;
		}

		$sourceQuestionId = $this->id;
		$sourceParentId = $this->getObjId();

		// make a real clone to keep the object unchanged
		$clone = clone $this;
		$clone->setId(-1);

		$clone->setObjId($targetParentId);

		if (!empty($targetQuestionTitle))
		{
			$clone->setTitle($targetQuestionTitle);
		}

		$clone->saveToDb();
		// copy question page content
		$clone->copyPageOfQuestion($sourceQuestionId);
		// copy XHTML media objects
		$clone->copyXHTMLMediaObjectsOfQuestion($sourceQuestionId);

		$clone->onCopy($sourceParentId, $sourceQuestionId, $clone->getObjId(), $clone->getId());

		return $clone->getId();
	}

	/**
	 * Synchronize a question with its original
	 * You need to extend this function if a question has additional data that needs to be synchronized
	 *
	 * @access public
	 */
	function syncWithOriginal()
	{
		parent::syncWithOriginal();
	}


	/**
	 * Get a submitted solution array from $_POST
	 *
	 * In general this may return any type that can be stored in a php session
	 * The return value is used by:
	 * 		savePreviewData()
	 * 		saveWorkingData()
	 * 		calculateReachedPointsForSolution()
	 *
	 * @return	array	('value1' => string|null, 'value2' => float|null)
	 */
	protected function getSolutionSubmit()
	{
		$value1 = trim(ilUtil::stripSlashes($_POST['question'.$this->getId().'value1']));
		$value2 = trim(ilUtil::stripSlashes($_POST['question'.$this->getId().'value2']));

		return array(
			'value1' => empty($value1)? null : (string) $value1,
			'value2' => empty($value2)? null : (float) $value2
		);
	}

	/**
	 * Get a stored solution for a user and test pass
	 * This is a wrapper to provide the same structure as getSolutionSubmit()
	 *
	 * @param int 	$active_id		active_id of hte user
	 * @param int	$pass			number of the test pass
	 * @param bool	$authorized		get the authorized solution
	 *
	 * @return	array	('value1' => string|null, 'value2' => float|null)
	 */
	public function getSolutionStored($active_id, $pass, $authorized = null)
	{
		// This provides an array with records from tst_solution
		// The example question should only store one record per answer
		// Other question types may use multiple records with value1/value2 in a key/value style
		if (isset($authorized))
		{
			// this provides either the authorized or intermediate solution
			$solutions = $this->getSolutionValues($active_id, $pass, $authorized);
		}
		else
		{
			// this provides the solution preferring the intermediate
			// or the solution from the previous pass
			$solutions = $this->getTestOutputSolutions($active_id, $pass);
		}


		if (empty($solutions))
		{
			// no solution stored yet
			$value1 = null;
			$value2 = null;
		}
		else
		{
			// If the process locker isn't activated in the Test and Assessment administration
			// then we may have multiple records due to race conditions
			// In this case the last saved record wins
			$solution = end($solutions);

			$value1 = $solution['value1'];
			$value2 = $solution['value2'];
		}

		return array(
			'value1' => empty($value1)? null : (string) $value1,
			'value2' => empty($value2)? null : (float) $value2
		);
	}


	/**
	 * Calculate the reached points from a solution array
	 *
	 * @param	array	('value1' => string, 'value2' => float)
	 * @return  float	reached points
	 */
	protected function calculateReachedPointsForSolution($solution)
	{
		// in our example we take the points entered by the student
		// and adjust them to be in the allowed range
		$points = (float) $solution['value2'];
		if ($points <= 0 || $points > $this->getMaximumPoints())
		{
			$points = 0;
		}

		// return the raw points given to the answer
		// these points will afterwards be adjusted by the scoring options of a test
		return $points;
	}


	/**
	 * Returns the points, a learner has reached answering the question
	 * The points are calculated from the given answers.
	 *
	 * @param int $active_id
	 * @param integer $pass The Id of the test pass
	 * @param bool $authorizedSolution
	 * @param boolean $returndetails (deprecated !!)
	 * @return int
	 *
	 * @throws ilTestException
	 */
	public function calculateReachedPoints($active_id, $pass = NULL, $authorizedSolution = true, $returndetails = false)
	{
		if( $returndetails )
		{
			throw new ilTestException('return details not implemented for '.__METHOD__);
		}

		if(is_null($pass))
		{
			$pass = $this->getSolutionMaxPass($active_id);
		}

		// get the answers of the learner from the tst_solution table
		// the data is saved by saveWorkingData() in this class
		$solution = $this->getSolutionStored($active_id, $pass, $authorizedSolution);

		return $this->calculateReachedPointsForSolution($solution);
	}


	/**
	 * Saves the learners input of the question to the database.
	 *
	 * @param integer $active_id 	Active id of the user
	 * @param integer $pass 		Test pass
	 * @param boolean $authorized	The solution is authorized
	 *
	 * @return boolean $status
	 */
	function saveWorkingData($active_id, $pass = NULL, $authorized = true)
	{
		if (is_null($pass))
		{
			$pass = ilObjTest::_getPass($active_id);
		}

		// get the submitted solution
		$solution = $this->getSolutionSubmit();

		$entered_values = 0;

		// save the submitted values avoiding race conditions
		$this->getProcessLocker()->executeUserSolutionUpdateLockOperation(function() use (&$entered_values, $solution, $active_id, $pass, $authorized) {


			$entered_values = isset($solution['value1']) || isset($solution['value2']);

			if ($authorized)
			{
				// a new authorized solution will delete the old one and the intermediate
				$this->removeExistingSolutions($active_id, $pass);
			}
			elseif ($entered_values)
			{
				// an new intermediate solution will only delete a previous one
				$this->removeIntermediateSolution($active_id, $pass);
			}

			if ($entered_values)
			{
				$this->saveCurrentSolution($active_id, $pass, $solution['value1'],  $solution['value2'], $authorized);
			}
		});


		// Log whether the user entered values
		if (ilObjAssessmentFolder::_enabledAssessmentLogging())
		{
			assQuestion::logAction($this->lng->txtlng(
				'assessment',
				$entered_values ? 'log_user_entered_values' : 'log_user_not_entered_values',
				ilObjAssessmentFolder::_getLogLanguage()
			),
				$active_id,
				$this->getId()
			);
		}

		// submitted solution is valid
		return true;
	}


	/**
	 * Reworks the allready saved working data if neccessary
	 * @param integer $active_id
	 * @param integer $pass
	 * @param boolean $obligationsAnswered
	 * @param boolean $authorized
	 */
	protected function reworkWorkingData($active_id, $pass, $obligationsAnswered, $authorized)
	{
		// normally nothing needs to be reworked
	}


	/**
	 * Creates an Excel worksheet for the detailed cumulated results of this question
	 *
	 * @param object $worksheet    Reference to the parent excel worksheet
	 * @param int $startrow     Startrow of the output in the excel worksheet
	 * @param int $active_id    Active id of the participant
	 * @param int $pass         Test pass
	 *
	 * @return int
	 */
	public function setExportDetailsXLS($worksheet, $startrow, $active_id, $pass)
	{
		$worksheet->setFormattedExcelTitle($worksheet->getColumnCoord(0) . $startrow, $this->getPlugin()->txt('assSQLQuestion'));
		$worksheet->setFormattedExcelTitle($worksheet->getColumnCoord(1) . $startrow, $this->getTitle());

		$solution = $this->getSolutionStored($active_id, $pass, true);
		$value1 = isset($solution['value1']) ? $solution['value1'] : '';
		$value2 = isset($solution['value2']) ? $solution['value2'] : '';

		$row = $startrow + 1;

		$worksheet->setCell($row, 0, $this->plugin->txt('label_value1'));
		$worksheet->setBold($worksheet->getColumnCoord(0) . $row);
		$worksheet->setCell($row, 1, $value1);
		$row++;

		$worksheet->setCell($row, 0, $this->plugin->txt('label_value2'));
		$worksheet->setBold($worksheet->getColumnCoord(0) . $row);
		$worksheet->setCell($row, 1, $value2);
		$row++;

		return $row + 1;
	}

	/**
	 * Creates a question from a QTI file
	 *
	 * Receives parameters from a QTI parser and creates a valid ILIAS question object
	 *
	 * @param object $item The QTI item object
	 * @param integer $questionpool_id The id of the parent questionpool
	 * @param integer $tst_id The id of the parent test if the question is part of a test
	 * @param object $tst_object A reference to the parent test object
	 * @param integer $question_counter A reference to a question counter to count the questions of an imported question pool
	 * @param array $import_mapping An array containing references to included ILIAS objects
	 * @access public
	 */
	function fromXML(&$item, &$questionpool_id, &$tst_id, &$tst_object, &$question_counter, &$import_mapping)
	{
		$this->getPlugin()->includeClass("import/qti12/class.assSQLQuestionImport.php");
		$import = new assSQLQuestionImport($this);
		$import->fromXML($item, $questionpool_id, $tst_id, $tst_object, $question_counter, $import_mapping);
	}

	/**
	 * Returns a QTI xml representation of the question and sets the internal
	 * domxml variable with the DOM XML representation of the QTI xml representation
	 *
	 * @return string The QTI xml representation of the question
	 * @access public
	 */
	function toXML($a_include_header = true, $a_include_binary = true, $a_shuffle = false, $test_output = false, $force_image_references = false)
	{
		$this->getPlugin()->includeClass("export/qti12/class.assSQLQuestionExport.php");
		$export = new assSQLQuestionExport($this);
		return $export->toXML($a_include_header, $a_include_binary, $a_shuffle, $test_output, $force_image_references);
	}
}

?>
