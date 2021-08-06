<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\browsericon\migrations;

use phpbb\db\migration\migration;

class browsericon_schema extends migration
{
	public function update_schema()
	{
		return 	[
			'add_columns' => [
				$this->table_prefix . 'posts' => [
					'user_agent' => ['VCHAR:255', null],
				],
			],
		];
	}

	public function revert_schema()
	{
		return 	[
			'drop_columns' => [
				$this->table_prefix . 'posts' => ['user_agent'],
			],
		];
	}
}
