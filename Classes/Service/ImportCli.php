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
	 * @var CLI return code: Error on given command.
	 */
	const CLI_ERROR_COMMAND = 1;

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
				. 'import - Imports the WURFL resource file, '
				. 'either from local or from remote source';

		$this->cli_help['examples']
			= 'Import local WURFL resource file.' . "\n"
				. '    php typo3/cli_dispatch.phpsh contexts_wurfl import --type local';

		$this->cli_options[]
			= array('--type local|remote', 'Source of resource file.');
		$this->cli_options[]
			= array('--force-update', 'Force update');

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
		if (!$this->cli_isArg('--type')) {
			$this->cli_echo('You need to specify the -s option.' . "\n", true);
			return self::CLI_ERROR_COMMAND;
		}

		$type = $this->cli_argValue('--type');

		try {
			if ($type === 'remote') {
				$this->importRemote($this->cli_isArg('--force-update'));
			} elseif ($type === 'local') {
				$this->importLocal();
			}
		}
		catch (Exception $exception) {
			$this->cli_echo($exception->getMessage() . "\n", true);
			return self::CLI_ERROR;
		}

		return self::CLI_OK;
	}

	/**
	 * Imports a local version of a WURFL resource file.
	 *
	 * @return boolean
	 */
	protected function importLocal()
	{
		$this->cli_echo('Beginning import from local source.' . "\n");

		$importer = new Tx_Contexts_Wurfl_Api_Model_Import(
			TeraWurflUpdater::SOURCE_LOCAL
		);

		$this->showStatus(
			$importer->import(),
			$importer->getUpdater()
		);

		return true;
	}

	/**
	 * Imports the WURFL resource file from the specified source
	 * (local or remote).
	 *
	 * @param boolean $force Force update (only valid for type "remote")
	 *
	 * @return boolean
	 */
	protected function importRemote($force)
	{
		$this->cli_echo('Beginning import from remote source.' . "\n");

		$importer = new Tx_Contexts_Wurfl_Api_Model_Import(
			TeraWurflUpdater::SOURCE_REMOTE
		);

		$result = $importer->import($force);

		// No update available, WURFL data is already up to date
		if ($result === Tx_Contexts_Wurfl_Api_Model_Import::STATUS_NO_UPDATE) {
			$this->cli_echo(
				'No update necessary. Your WURFL data is already up to date.'
				. ' Use --force-update to update anyway.'
				. "\n"
			);
			return true;
		}

		$this->showStatus($result, $importer->getUpdater());

		return true;
	}

	/**
	 * Show status of update.
	 *
	 * @param boolean          $status  TRUE/FALSE
	 * @param TeraWurflUpdater $updater WURFL updater instance
	 *
	 * @return void
	 */
	protected function showStatus($status, TeraWurflUpdater $updater)
	{
		if ($status) {
			$this->cli_echo('Database Update OK' . "\n");

			$this->cli_echo(
				'WURFL Version: '
				. $updater->loader->version
				. ' (' . $updater->loader->last_updated . ')'
				. "\n"
			);
			$this->cli_echo(
				'WURFL Devices: '
				. $updater->loader->mainDevices . "\n"
			);
			$this->cli_echo(
				'PATCH New Devices: '
				. $updater->loader->patchAddedDevices . "\n"
			);
			$this->cli_echo(
				'PATCH Merged Devices: '
				. $updater->loader->patchMergedDevices . "\n"
			);

			if (count($updater->loader->errors) > 0) {
				$this->cli_echo('Errors:' . "\n");

				foreach ($updater->loader->errors as $error) {
					$this->cli_echo($error . "\n");
				}

				$this->cli_echo("\n");
			}
		} else {
			$this->cli_echo('ERROR LOADING DATA!' . "\n");
			$this->cli_echo('Errors:' . "\n");
			$this->cli_echo("\n");

			foreach ($updater->loader->errors as $error) {
				$this->cli_echo($error . "\n");
			}

			$this->cli_echo("\n");
		}
	}
}
?>
