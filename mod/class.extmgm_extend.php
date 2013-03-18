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
 * Shows an information box on the extension detail page
 * in the extension manager.
 *
 * @category   Contexts
 * @package    WURFL
 * @subpackage Configuration
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 */
class tx_contextswurfl_extmgmextend
{
	/**
	 * Generates and returns the information message
	 *
	 * @return string HTML code
	 */
	public function displayMessage()
	{
		return
<<<HTML
	<div style="position: absolute; top: 40px; right: 20px; width: 300px;">
		<div class="typo3-message message-information">
			<strong>contexts_wurfl actions</strong>
			<ul>
				<li><a href="/typo3conf/ext/contexts_wurfl/mod/updateDbLocal.php">Update db from local file</a></li>
				<li><a href="/typo3conf/ext/contexts_wurfl/mod/updateDbRemote.php">Update db from remote repository</a></li>
			</ul>
		</div>
	</div>
HTML;
	}
}
?>
