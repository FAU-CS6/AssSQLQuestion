<?php

/**
 * GUI class of the SQLQuestion plugin
 *
 * @author Dominik Probst <dominik.probst@studium.fau.de>
 * @version	$Id:  $
 * @ingroup ModulesTestQuestionPool
 *
 * @ilctrl_iscalledby assSQLQuestionGUI: ilObjQuestionPoolGUI, ilObjTestGUI, ilQuestionEditGUI, ilTestExpressPageObjectGUI
 */
class assSQLQuestionGUI extends assQuestionGUI
{
	/**
 	 * @const	string URL base path for including used javascript and css files
 	 */
 	const URL_PATH = "./Customizing/global/plugins/Modules/TestQuestionPool/Questions/assSQLQuestion";

	/**
	 * @var ilassSQLQuestionPlugin The plugin object
	 */
	var $plugin = null;

	/**
	 * @var assSQLQuestion The question object
	 */
	var $object = null;

	/**
	* Constructor
	*
	* @param integer $id The database id of a question object
	* @access public
	*/
	public function __construct($id = -1)
	{
		parent::__construct();

		$this->plugin = ilPlugin::getPluginObject(IL_COMP_MODULE, "TestQuestionPool", "qst", "assSQLQuestion");
		$this->plugin->includeClass("class.assSQLQuestion.php");
		$this->object = new assSQLQuestion();
		if ($id >= 0)
		{
			$this->object->loadFromDb($id);
		}
	}

	/**
	 * Private helper function to prepare the different GUIs by adding required
	 * Javascript and CSS files
	 *
	 * @access private
	 */
	 private function prepareTemplate()
	 {
			// Add CSS files

				// Custom css
				$this->tpl->addCss(self::URL_PATH.'/css/custom.css');

				// Codemirror
				$this->tpl->addCss(self::URL_PATH.'/lib/codemirror/lib/codemirror.css');

		  // Add JS files

				// Custom Files
        $this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlResult.js');
				$this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlRun.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/handler/handlerAbstract.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/handler/handlerEditQuestion.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlRunErrors/sqlRunErrorAbstract.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlRunErrors/sqlRunErrorDBCreation.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlRunErrors/sqlRunErrorIntegrityCheck.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlRunErrors/sqlRunErrorNoVisibleResult.js');
        $this->tpl->addJavascript(self::URL_PATH.'/js/sql/sqlRunErrors/sqlRunErrorRunningSequence.js');

			  // Codemirror
				$this->tpl->addJavascript(self::URL_PATH.'/lib/codemirror/lib/codemirror.js');
				$this->tpl->addJavascript(self::URL_PATH.'/lib/codemirror/mode/sql/sql.js');

				// SQL.js
				$this->tpl->addJavascript(self::URL_PATH.'/lib/sql.js/sql.js');

			// Add custom js code

				// Add path to the plugin file to be accessible in js, too
        $this->tpl->addOnLoadCode("window.URL_PATH = \"".self::URL_PATH."\"");


	 }

	 /**
	  * Private helper function to keep editQuestion more clean
		* Implements the addition of question specific fields used in editQuestion
		*
		* @param ilPropertyFormGUI $form The form the fields should be added to
		* @access private
		*/
	private function addSpecificQuestionFormProperties(\ilPropertyFormGUI $form)
	{
		global $lng;

    // Information on SQL sequences
    $sequences_info = new ilCustomInputGUI($this->plugin->txt('sequences_info'));
    $sequences_info->setInfo($this->plugin->txt('sequences_info_text'));
    $sequences_info->setRequired(true);
    $form->addItem($sequences_info);

    // Input fields for the sql sequences A, B and C

		// Sequence A
		$sequence_a_textarea = new ilCustomInputGUI($this->plugin->txt('sequence_a'));
		$sequence_a_textarea->setHTML($this->createCodeEditorInput("sequence_a", ""));
		$form->addItem($sequence_a_textarea);

    // Sequence B
		$sequence_b_textarea = new ilCustomInputGUI($this->plugin->txt('sequence_b'));
		$sequence_b_textarea->setHTML($this->createCodeEditorInput("sequence_b", ""));
		$form->addItem($sequence_b_textarea);

    // Sequence C
		$sequence_c_textarea = new ilCustomInputGUI($this->plugin->txt('sequence_c'));
		$sequence_c_textarea->setHTML($this->createCodeEditorInput("sequence_c", ""));
		$form->addItem($sequence_c_textarea);

    // Checkbox to activate and deaktivate the integrity check
    $integrity_check = new ilCheckboxInputGUI($this->plugin->txt('integrity_check'), 'integrity_check');
		$form->addItem($integrity_check);

		// Execute button
		$execute_button = new ilCustomInputGUI('');
		$execute_button->setHTML('<input type="button" class="btn-default btn-sm btn" id="il_as_qpl_qpisql_execution_button" value="Execute" onclick="handlerEditQuestion.execute()">');
		$form->addItem($execute_button);

    // Information on output area
    $output_info = new ilCustomInputGUI($this->plugin->txt('output_info'));
    $output_info->setInfo($this->plugin->txt('output_info_text'));
    $output_info->setRequired(true);
    $form->addItem($output_info);

    // Output
		$output_area = new ilCustomInputGUI('');
		$output_area->setHTML($this->createOutputArea());
		$form->addItem($output_area);

    // Scoring information
    $scoring_info = new ilCustomInputGUI($this->plugin->txt('scoring_info'));
    $scoring_info->setInfo($this->plugin->txt('scoring_info_text'));
    $scoring_info->setRequired(true);
    $form->addItem($scoring_info);

    // Scoring metric: result lines

    // Scoring metric: result lines - information
    $scoring_metric_result_lines_info = new ilCustomInputGUI('');
    $scoring_metric_result_lines_info->setInfo($this->plugin->txt('scoring_metric_result_lines_info'));
    $form->addItem($scoring_metric_result_lines_info);

    // Scoring metric: result lines - form
    $scoring_metric_result_lines_form = new ilCustomInputGUI('');
		$scoring_metric_result_lines_form->setHTML($this->createScoringArea('points_result_lines',
                                                                        $this->plugin->txt('scoring_metric_result_lines_output_text'),
                                                                        'il_as_qpl_qpisql_scoring_metric_result_lines_output',
                                                                        'il_as_qpl_qpisql_scoring_metric_result_lines_output_hidden_field_name',
                                                                        'il_as_qpl_qpisql_scoring_metric_result_lines_output_hidden_field_id'));
		$form->addItem($scoring_metric_result_lines_form);

	}

	/**
	 * Helper function to generate a single code editor element
	 *
	 * @param string $name The name of the code editor element
	 * @param string $value The default value of the field
	 * @access private
	 */
	private function createCodeEditorInput($name, $value)
	{
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_edit_code.html');
		$tpl->setVariable("CONTENT", ilUtil::prepareFormOutput($value));
		$tpl->setVariable("NAME", $name);
		$tpl->setVariable("QUESTION_ID", $this->object->getId());
    $tpl->setVariable("PAGEHANDLER", "handlerEditQuestion");
		$item = new ilCustomInputGUI('');

		return $tpl->get();
	}

  /**
	 * Helper function to generate the output area html code
	 *
	 * @access private
	 */
	private function createOutputArea()
	{
    $tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_output_area.html');
		$tpl->setVariable("NO_EXECUTION", $this->plugin->txt('no_execution'));
		$tpl->setVariable("EXECUTION_RUNNING", $this->plugin->txt('execution_running'));
		$tpl->setVariable("ERROR_DB_CREATION", $this->plugin->txt('error_db_creation'));
    $tpl->setVariable("ERROR_INTEGRITY_CHECK", $this->plugin->txt('error_integrity_check'));
    $tpl->setVariable("ERROR_NO_VISIBLE_RESULT", $this->plugin->txt('error_no_visible_result'));
    $tpl->setVariable("ERROR_RUNNING_SEQUENCE", $this->plugin->txt('error_running_sequence'));
		$item = new ilCustomInputGUI('');

		return $tpl->get();
	}

  /**
	 * Helper function to generate a single scoring area
	 *
	 * @param string $point_field_name The name of the form field holding the selected points
	 * @param string $output_text A custom text to describe the computed output
   * @param string $output_field_id The id of the div to write a output
   * @param string $hidden_field_name The name of the hidden form element to save computed the metric value
   * @param string $hidden_field_id The id of the hidden form element to save computed the metric value
	 * @access private
	 */
	private function createScoringArea($point_field_name, $output_text,
                                      $output_field_id, $hidden_field_name,
                                      $hidden_field_id)
	{
		$tpl = $this->plugin->getTemplate('tpl.il_as_qpl_qpisql_scoring_area.html');
		$tpl->setVariable("POINT_FIELD_NAME", $point_field_name);
		$tpl->setVariable("POINTS_TEXT", $this->plugin->txt('points_text'));
    $tpl->setVariable("EXECUTE_FIRST_WARNING", $this->plugin->txt('scoring_area_execute_first'));
		$tpl->setVariable("OUTPUT_TEXT", $output_text);
    $tpl->setVariable("OUTPUT_FIELD_ID", $output_field_id);
    $tpl->setVariable("HIDDEN_FIELD_NAME", $hidden_field_name);
    $tpl->setVariable("HIDDEN_FIELD_ID", $hidden_field_id);
		$item = new ilCustomInputGUI('');

		return $tpl->get();
	}

	/**
	 * Creates an output of the edit form for the question
	 *
	 * @param bool $checkonly
	 * @return bool
	 */
	public function editQuestion($checkonly = false)
	{
		// Initialize the Language module
		global $DIC;
		$lng = $DIC->language();

		// Prepare the template by loading css and javascript files
		$this->prepareTemplate();

		// Initialize the form
		$form = new ilPropertyFormGUI();
		$form->setFormAction($this->ctrl->getFormAction($this));
		$form->setTitle($this->outQuestionType());
		$form->setDescription($this->plugin->txt('question_edit_info'));
		$form->setMultipart(TRUE);
		$form->setTableWidth("100%");
		$form->setId("qpisql");

		// Add basic fields (title, author, description, question and working time)
		$this->addBasicQuestionFormProperties($form);

		// Add question specific fields
		// As we have to add a bunch of them we created a separate function for this
		$this->addSpecificQuestionFormProperties($form);

		// Add the final form buttons
		$this->populateTaxonomyFormSection($form);
		$this->addQuestionFormCommandButtons($form);

		// There should not be any errors until now
		$errors = false;

		// If the question is to be saved
		if ($this->isSaveCommand())
		{
			// Set the values to the ones send by POST
			$form->setValuesByPost();

			// checkInput() checks and transforms the input
			// If there are errors set errors to true
			$errors = !$form->checkInput();

			// Set the values to the ones send by POST because checkInput
			// may have changed something
			$form->setValuesByPost();

			if ($errors)
			{
				$checkonly = false;
			}
		}

		if (!$checkonly)
		{
			$this->getQuestionTemplate();
			$this->tpl->setVariable("QUESTION_DATA", $form->getHTML());
		}

		return $errors;
	}

	/**
	 * Evaluates a posted edit form and writes the form data in the question object
	 *
	 * @param bool $always
	 * @return integer A positive value, if one of the required fields wasn't set, else 0
	 */
	public function writePostData($always = false)
	{
		$hasErrors = (!$always) ? $this->editQuestion(true) : false;
		if (!$hasErrors)
		{
			$this->writeQuestionGenericPostData();

			// Here you can write the question type specific values
			// Some question types define the maximum points directly,
			// other calculate them from other properties
			$this->object->setPoints((int) $_POST["points"]);

			$this->saveTaxonomyAssignments();
			return 0;
		}
		return 1;
	}


	/**
	 * Get the HTML output of the question for a test
	 * (this function could be private)
	 *
	 * @param integer $active_id						The active user id
	 * @param integer $pass								The test pass
	 * @param boolean $is_postponed						Question is postponed
	 * @param boolean $use_post_solutions				Use post solutions
	 * @param boolean $show_specific_inline_feedback	Show a specific inline feedback
	 * @return string
	 */
	public function getTestOutput($active_id, $pass = NULL, $is_postponed = FALSE, $use_post_solutions = FALSE, $show_specific_inline_feedback = FALSE)
	{
		if (is_null($pass))
		{
			$pass = ilObjTest::_getPass($active_id);
		}

		$solution = $this->object->getSolutionStored($active_id, $pass, null);
		$value1 = isset($solution["value1"]) ? $solution["value1"] : "";
		$value2 = isset($solution["value2"]) ? $solution["value2"] : "";

		// fill the question output template
		// in out example we have 1:1 relation for the database field
		$template = $this->plugin->getTemplate("tpl.il_as_qpl_qpisql_output.html");

		$template->setVariable("QUESTION_ID", $this->object->getId());
		$questiontext = $this->object->getQuestion();
		$template->setVariable("QUESTIONTEXT", $this->object->prepareTextareaOutput($questiontext, TRUE));
		$template->setVariable("LABEL_VALUE1", $this->plugin->txt('label_value1'));
		$template->setVariable("LABEL_VALUE2", $this->plugin->txt('label_value2'));

		$template->setVariable("VALUE1", ilUtil::prepareFormOutput($value1));
		$template->setVariable("VALUE2", ilUtil::prepareFormOutput($value2));

		$questionoutput = $template->get();
		$pageoutput = $this->outQuestionPage("", $is_postponed, $active_id, $questionoutput);
		return $pageoutput;
	}


	/**
	 * Get the output for question preview
	 * (called from ilObjQuestionPoolGUI)
	 *
	 * @param boolean	$show_question_only 	show only the question instead of embedding page (true/false)
	 * @param boolean	$show_question_only
	 * @return string
	 */
	public function getPreview($show_question_only = FALSE, $showInlineFeedback = FALSE)
	{
		if( is_object($this->getPreviewSession()) )
		{
			$solution = $this->getPreviewSession()->getParticipantsSolution();
		}
		else
		{
			$solution = array('value1' => null, 'value2' => null);
		}

		// Fill the template with a preview version of the question
		$template = $this->plugin->getTemplate("tpl.il_as_qpl_qpisql_output.html");
		$questiontext = $this->object->getQuestion();
		$template->setVariable("QUESTIONTEXT", $this->object->prepareTextareaOutput($questiontext, TRUE));
		$template->setVariable("QUESTION_ID", $this->object->getId());
		$template->setVariable("LABEL_VALUE1", $this->plugin->txt('label_value1'));
		$template->setVariable("LABEL_VALUE2", $this->plugin->txt('label_value2'));

		$template->setVariable("VALUE1", ilUtil::prepareFormOutput($solution['value1']));
		$template->setVariable("VALUE2", ilUtil::prepareFormOutput($solution['value2']));

		$questionoutput = $template->get();
		if(!$show_question_only)
		{
			// get page object output
			$questionoutput = $this->getILIASPage($questionoutput);
		}
		return $questionoutput;
	}

	/**
	 * Get the question solution output
	 * @param integer $active_id             The active user id
	 * @param integer $pass                  The test pass
	 * @param boolean $graphicalOutput       Show visual feedback for right/wrong answers
	 * @param boolean $result_output         Show the reached points for parts of the question
	 * @param boolean $show_question_only    Show the question without the ILIAS content around
	 * @param boolean $show_feedback         Show the question feedback
	 * @param boolean $show_correct_solution Show the correct solution instead of the user solution
	 * @param boolean $show_manual_scoring   Show specific information for the manual scoring output
	 * @param bool    $show_question_text

	 * @return string solution output of the question as HTML code
	 */
	function getSolutionOutput(
		$active_id,
		$pass = NULL,
		$graphicalOutput = FALSE,
		$result_output = FALSE,
		$show_question_only = TRUE,
		$show_feedback = FALSE,
		$show_correct_solution = FALSE,
		$show_manual_scoring = FALSE,
		$show_question_text = TRUE
	)
	{
		// get the solution of the user for the active pass or from the last pass if allowed
		if (($active_id > 0) && (!$show_correct_solution))
		{
			$solution = $this->object->getSolutionStored($active_id, $pass, true);
			$value1 = isset($solution["value1"]) ? $solution["value1"] : "";
			$value2 = isset($solution["value2"]) ? $solution["value2"] : "";
		}
		else
		{
			// show the correct solution
			$value1 =  $this->plugin->txt("any_text");
			$value2 = $this->object->getMaximumPoints();
		}

		// get the solution template
		$template = $this->plugin->getTemplate("tpl.il_as_qpl_qpisql_output_solution.html");
		$solutiontemplate = new ilTemplate("tpl.il_as_tst_solution_output.html", TRUE, TRUE, "Modules/TestQuestionPool");

		if (($active_id > 0) && (!$show_correct_solution))
		{
			if ($graphicalOutput)
			{
				// copied from assNumericGUI, yet not really understood
				if($this->object->getStep() === NULL)
				{
					$reached_points = $this->object->getReachedPoints($active_id, $pass);
				}
				else
				{
					$reached_points = $this->object->calculateReachedPoints($active_id, $pass);
				}

				// output of ok/not ok icons for user entered solutions
				// in this example we have ony one relevant input field (points)
				// so we just need to set the icon beneath this field
				// question types with partial answers may have a more complex output
				if ($reached_points == $this->object->getMaximumPoints())
				{
					$template->setCurrentBlock("icon_ok");
					$template->setVariable("ICON_OK", ilUtil::getImagePath("icon_ok.svg"));
					$template->setVariable("TEXT_OK", $this->lng->txt("answer_is_right"));
					$template->parseCurrentBlock();
				}
				else
				{
					$template->setCurrentBlock("icon_ok");
					$template->setVariable("ICON_NOT_OK", ilUtil::getImagePath("icon_not_ok.svg"));
					$template->setVariable("TEXT_NOT_OK", $this->lng->txt("answer_is_wrong"));
					$template->parseCurrentBlock();
				}
			}
		}

		// fill the template variables
		// adapt this to your structure of answers
		$template->setVariable("LABEL_VALUE1", $this->plugin->txt('label_value1'));
		$template->setVariable("LABEL_VALUE2", $this->plugin->txt('label_value2'));

		$template->setVariable("VALUE1", empty($value1) ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : ilUtil::prepareFormOutput($value1));
		$template->setVariable("VALUE2", empty($value2) ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : ilUtil::prepareFormOutput($value2));

		$questiontext = $this->object->getQuestion();
		if ($show_question_text==true)
		{
			$template->setVariable("QUESTIONTEXT", $this->object->prepareTextareaOutput($questiontext, TRUE));
		}

		$questionoutput = $template->get();

		$feedback = ($show_feedback && !$this->isTestPresentationContext()) ? $this->getGenericFeedbackOutput($active_id, $pass) : "";
		if (strlen($feedback))
		{
			$cssClass = ( $this->hasCorrectSolution($active_id, $pass) ?
				ilAssQuestionFeedback::CSS_CLASS_FEEDBACK_CORRECT : ilAssQuestionFeedback::CSS_CLASS_FEEDBACK_WRONG
			);

			$solutiontemplate->setVariable("ILC_FB_CSS_CLASS", $cssClass);
			$solutiontemplate->setVariable("FEEDBACK", $this->object->prepareTextareaOutput( $feedback, true ));

		}
		$solutiontemplate->setVariable("SOLUTION_OUTPUT", $questionoutput);

		$solutionoutput = $solutiontemplate->get();
		if(!$show_question_only)
		{
			// get page object output
			$solutionoutput = $this->getILIASPage($solutionoutput);
		}
		return $solutionoutput;
	}

	/**
	 * Returns the answer specific feedback for the question
	 *
	 * @param integer $active_id Active ID of the user
	 * @param integer $pass Active pass
	 * @return string HTML Code with the answer specific feedback
	 * @access public
	 */
	function getSpecificFeedbackOutput($active_id, $pass)
	{
		// By default no answer specific feedback is defined
		$output = '';
		return $this->object->prepareTextareaOutput($output, TRUE);
	}


	/**
	* Sets the ILIAS tabs for this question type
	* called from ilObjTestGUI and ilObjQuestionPoolGUI
	*/
	public function setQuestionTabs()
	{
		global $DIC;
		$rbacsystem = $DIC->rbac()->system();
		$ilTabs = $DIC->tabs();

		$this->ctrl->setParameterByClass("ilpageobjectgui", "q_id", $_GET["q_id"]);
		include_once "./Modules/TestQuestionPool/classes/class.assQuestion.php";
		$q_type = $this->object->getQuestionType();

		if (strlen($q_type))
		{
			$classname = $q_type . "GUI";
			$this->ctrl->setParameterByClass(strtolower($classname), "sel_question_types", $q_type);
			$this->ctrl->setParameterByClass(strtolower($classname), "q_id", $_GET["q_id"]);
		}

		if ($_GET["q_id"])
		{
			if ($rbacsystem->checkAccess('write', $_GET["ref_id"]))
			{
				// edit page
				$ilTabs->addTarget("edit_page",
					$this->ctrl->getLinkTargetByClass("ilAssQuestionPageGUI", "edit"),
					array("edit", "insert", "exec_pg"),
					"", "", $force_active);
			}

			$this->addTab_QuestionPreview($ilTabs);
		}

		$force_active = false;
		if ($rbacsystem->checkAccess('write', $_GET["ref_id"]))
		{
			$url = "";

			if ($classname) $url = $this->ctrl->getLinkTargetByClass($classname, "editQuestion");
			$commands = $_POST["cmd"];

			// edit question properties
			$ilTabs->addTarget("edit_properties",
				$url,
				array("editQuestion", "save", "cancel", "saveEdit", "originalSyncForm"),
				$classname, "", $force_active);
		}

		// add tab for question feedback within common class assQuestionGUI
		$this->addTab_QuestionFeedback($ilTabs);

		// add tab for question hint within common class assQuestionGUI
		$this->addTab_QuestionHints($ilTabs);

		// add tab for question's suggested solution within common class assQuestionGUI
		$this->addTab_SuggestedSolution($ilTabs, $classname);


		// Assessment of questions sub menu entry
		if ($_GET["q_id"])
		{
			$ilTabs->addTarget("statistics",
				$this->ctrl->getLinkTargetByClass($classname, "assessment"),
				array("assessment"),
				$classname, "");
		}

		$this->addBackTab($ilTabs);
	}
}
?>
