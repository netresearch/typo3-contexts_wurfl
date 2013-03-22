<?php
declare(encoding = 'UTF-8');

/**
 * Extension configuration integration.
 *
 * PHP version 5
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Configuration
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */

/**
 * Shows an information box on the extension configuration page
 * in the extension manager.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Configuration
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */
class Tx_Contexts_Wurfl_Service_ExtManager
{
	/**
	 * Generates and returns the information message.
	 *
	 * @param array               $params      Name and value from ext_conf_template.txt
	 * @param t3lib_tsStyleConfig $styleConfig Instance of config style editor
	 *
	 * @return string HTML code
	 */
	public function displayMessage(
		array $params, t3lib_tsStyleConfig $styleConfig
	) {
		return
<<<HTML
	<style type="text/css">
		.wurfl-ext-manager ul {
			list-style: circle;
			padding-left: 16px;
		}
		.wurfl-ext-manager ul li {
			margin: 0px;
		}
		.wurfl-ext-manager ul li a {
			text-decoration: underline;
		}
		.wurfl-ext-manager ul li a:hover {
			text-decoration: none;
		}
	</style>
	<div class="typo3-message message-information wurfl-ext-manager">
		<ul>
			<li>
				<a href="/typo3conf/ext/contexts_wurfl/Scripts/ImportDbLocal.php">
					Update wurfl db from local file</a>
			</li>
			<li>
				<a href="/typo3conf/ext/contexts_wurfl/Scripts/ImportDbRemote.php">
					Update wurfl db from remote repository (forced)</a>
			</li>
		</ul>
	</div>
HTML;
	}
}
?>
