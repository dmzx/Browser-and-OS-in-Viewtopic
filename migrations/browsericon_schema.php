<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\browsericon\migrations;

class browsericon_schema extends \phpbb\db\migration\migration
{
	public function update_schema()
	{
		return 	array(
			'add_columns' => array(
				$this->table_prefix . 'posts' => array(
					'user_agent' => array('VCHAR:255', null),
				),
			),
		);
	}

	public function revert_schema()
	{
		return 	array(
			'drop_columns' => array(
				$this->table_prefix . 'posts' => array('user_agent'),
			),
		);
	}
}
