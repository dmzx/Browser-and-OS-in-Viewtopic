<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\browsericon;

use phpbb\extension\base;

/**
* Extension class for custom enable/disable/purge actions
*/
class ext extends base
{
	/**
	* Enable extension if phpBB version requirement is met
	*
	* @return bool
	* @access public
	*/
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return version_compare($config['version'], '3.1.3-RC1', '>=');
	}
}
