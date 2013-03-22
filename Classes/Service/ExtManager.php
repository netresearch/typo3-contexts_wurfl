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
	 * @return string HTML code
	 */
	public function displayMessage()
	{
		return
<<<HTML
	<div style="position: absolute; top: 40px; right: 20px; width: 400px;">
		<div class="typo3-message message-information">
			<strong>contexts_wurfl actions:</strong>
			<ul>
				<li style="margin: 0px;">
					<a href="/typo3conf/ext/contexts_wurfl/Scripts/ImportDbLocal.php">
						Update wurfl db from local file</a>
				</li>
				<li style="margin: 0px;">
					<a href="/typo3conf/ext/contexts_wurfl/Scripts/ImportDbRemote.php">
						Update wurfl db from remote repository (forced)</a>
				</li>
			</ul>
		</div>
	</div>
HTML;
	}
}
?>
