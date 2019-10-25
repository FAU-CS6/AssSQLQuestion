# Question Plugin for ILIAS - SQL

The Open Source Learning Management System [ILIAS](https://www.ilias.de/) is a an extensive tool to support students at universities learning. One big feature of ILIAS is the testing module. Using this lecturers are enabled to give their students a test they have to solve before accessing important documents. Of course the testing module can be used to create real (online) exams, too.

While the testing module supports a bunch of different question types by default, including [Single Choice](https://docu.ilias.de/ilias.php?ref_id=2221&obj_id=42830&obj_type=StructureObject&cmd=layout&cmdClass=illmpresentationgui&cmdNode=2c&baseClass=ilLMPresentationGUI), [Multiple Choice](https://docu.ilias.de/ilias.php?ref_id=2221&obj_id=41931&cmd=layout&cmdClass=illmpresentationgui&cmdNode=2c&baseClass=ilLMPresentationGUI) and [Ordering](https://docu.ilias.de/ilias.php?ref_id=2221&obj_id=41720&cmd=layout&cmdClass=illmpresentationgui&cmdNode=2c&baseClass=ilLMPresentationGUI) questions, more complex question types have to be added by installing a plugin.

In this project - called ***Q**uestion **P**lugin for **I**LIAS - **SQL** (**QPI-SQL**)* - we developed an ILIAS plugin, named *assSQLQuestion*. The plugin enables lecturers to add interactive SQL questions to their tests. Students can execute their answers and their work is graded automatically by applying different metrics.

## Installation

There are two different options to install this plugin. One for demonstration and testing purposes and one to install the plugin to an existing ILIAS instance for production use.

### Installation as part of an existing ILIAS instance

1. Copy the content of this repository into the `Customizing/global/plugins/Modules/TestQuestionPool/Questions/assSQLQuestion` subfolder of your ILIAS installation. It might be necessary to create the subfolders if they do not exist.

1. Access Adminstration -> Plugins

1. Press "Install" for the assSQLQuestion plugin

1. Press "Activate" for the assSQLQuestion plugin

## Documentation
Additionally to the in source documentation there is a documentation educating about the interaction of components and further informations. Due to the origin of this project this part of the documentation is only available in German. It can be viewed by compiling the `dokumentation.tex` in the `docs/` folder with *pdflatex* or a comparable Latex compiler.

  ```
    cd docs/
    pdflatex dokumentation.tex
  ```


## Addtional Information

***QPI-SQL*** is a project started by Dominik Probst (Email: [me@dominik-probst.de](mailto:me@dominik-probst.de)) as part of his master's degree in 2018. The accompanying chair is the [*Chair of Computer Science 6*](https://www.cs6.tf.fau.eu) at [*Friedrich-Alexander University Erlangen-Nuremberg*](https://www.fau.eu/).
