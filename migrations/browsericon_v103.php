<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2021 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\browsericon\migrations;

use phpbb\db\migration\migration;

class browsericon_v103 extends migration
{
	public static function depends_on()
	{
		return [
			'\dmzx\browsericon\migrations\browsericon_schema'
		];
	}

	public function update_data()
	{
		return [
			// Add config
			['config.add', ['browsericon_version', '1.0.3']],
		];
	}
}
