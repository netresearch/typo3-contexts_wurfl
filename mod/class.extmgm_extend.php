<?php
declare(encoding = 'UTF-8');
/**
 * Coremetrics integration
 *
 * PHP version 5
 *
 * @category   Extensions
 * @package    TYPO3
 * @subpackage tx_nrcoremetrics
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    GPL3 http://www.gnu.org/licenses/gpl.html
 * @link       http://www.netresearch.de/
 */

/**
 * Shows the information box on the extension detail page
 * in the extension manager.
 *
 * This is pretty hacky, but the only way I see now. Idea stolen from tt_news 3.0.
 *
 * @category   Extensions
 * @package    TYPO3
 * @subpackage tx_nrcoremetrics
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    GPL3 http://www.gnu.org/licenses/gpl.html
 * @link       http://www.netresearch.de/
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
<<<XML
	<div style="position: absolute; top: 40px; right: 20px; width: 300px;">
		<div class="typo3-message message-information">
			<strong>contexts_wurfl actions</strong>
			<ul>
				<li><a href="/typo3conf/ext/contexts_wurfl/mod/updateDbLocal.php">Update db from local file</a></li>
				<li><a href="/typo3conf/ext/contexts_wurfl/mod/updateDbRemote.php">Update db from remote repository</a></li>
			</ul>
		</div>
	</div>
XML;
	}
}

?>
