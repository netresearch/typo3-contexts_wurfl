<?php
declare(encoding = 'UTF-8');

/**
 * WURFL contexts import module.
 *
 * PHP version 5
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * Class used to import WURFL data.
 *
 * @category Contexts
 * @package  WURFL
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Api_Model_Import
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
	}

	public function import()
	{
		$extConf = unserialize(
			$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['contexts_wurfl']
		);

		$xmlFile = $this->extract(
			$this->download($extConf['remoteRepository'])
		);

		$xml = new SimpleXMLElement($xmlFile, LIBXML_NOERROR, true);

var_dump($xml);
exit;

		return true;
	}

	/**
	 * Downloads the configured WURFL xml file.
	 *
	 * @return string
	 */
	protected function download($importUrl)
	{
		$remoteFile = fopen($importUrl, 'rb');

		if ($remoteFile) {
			$tempFile  = tempnam('/tmp', 'wurfl_');
			$localFile = fopen($tempFile, 'wb');

			if ($localFile) {
				while (!feof($remoteFile)) {
					fwrite($localFile, fread($remoteFile, 1024 * 8), 1024 * 8);
				}
			}
		}

		if ($localFile) {
			fclose($localFile);
		}

		if ($remoteFile) {
			fclose($remoteFile);
		}

		return $tempFile;
	}

	protected function extract($tempFile)
	{
		$zip     = zip_open($tempFile);
		$xmlFile = $tempFile . '_wurfl.xml';

		if ($zip) {
			if ($zip_entry = zip_read($zip)) {
				$fp = fopen($xmlFile, 'w');

				if (zip_entry_open($zip, $zip_entry, 'r')) {
					$buf = zip_entry_read(
						$zip_entry, zip_entry_filesize($zip_entry)
					);

					fwrite($fp, $buf);
					zip_entry_close($zip_entry);
					fclose($fp);
				}
			}

			zip_close($zip);
		}

		return $xmlFile;
	}
}
?>
