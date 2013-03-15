<?php
declare(encoding = 'UTF-8');

/**
 * WURFL contexts command line interface.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Service
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * This class provides a commandline script to import the WURFL data.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Service
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Service_ImportCli extends t3lib_cli
{
	/**
	 * CLI return code: All ok.
	 *
	 * @var integer
	 */
	const CLI_OK = 0;

	/**
	 * CLI return code: Error.
	 *
	 * @var integer
	 */
	const CLI_ERROR = 255;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Running parent class constructor
		parent::__construct();

		// Setting help texts:
		$this->cli_help['name']
			= 'contexts_wurfl';

		$this->cli_help['synopsis']
			= 'task ###OPTIONS###';

		$this->cli_help['description']
			= 'Import WURFL resource file.' . "\n"
				. 'import - Imports the WURFL resource file, updating the database';

		$this->cli_help['examples']
			= 'Import WURFL resource file.' . "\n"
				. '    php typo3/cli_dispatch.phpsh contexts_wurfl import';

		$this->cli_help['author'] = 'Rico Sonntag';
		$this->cli_help['available tasks'] = 'import';
	}

	/**
	 * Main CLI function with echo output.
	 *
	 * @return integer
	 */
	public function main()
	{
		// Validate input
		$this->cli_validateArgs();

		// Get task (function)
		$strTask = (string) $this->cli_args['_DEFAULT'][1];

		switch ($strTask) {
		case 'import':
			return $this->import();

		default:
			$this->cli_help();
			break;
		}

		return self::CLI_OK;
	}

	/**
	 * Task "import".
	 *
	 * @return integer CLI error code.
	 */
	protected function import()
	{
		try {
			$instance = new Tx_Contexts_Wurfl_Api_Model_Import();
			return $instance->import();
		}
		catch (Exception $exception) {
			$this->cli_echo($exception->getMessage() . "\n", true);
			return self::CLI_ERROR;
		}

		$this->cli_echo(
			'Import of WURFL resource file successfully finished.' . "\n"
		);

		return self::CLI_OK;
	}
}
?>
