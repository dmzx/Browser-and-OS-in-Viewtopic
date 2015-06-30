<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\browsericon\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\request\request */
	protected $request;

	public function __construct(\phpbb\request\request $request, $phpbb_root_path, $php_ext)
	{
		$this->request = $request;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_post_rowset_data'	=> 'viewtopic_post_rowset_data',
			'core.viewtopic_modify_post_row'	=> 'viewtopic_modify_post_row',
			'core.submit_post_modify_sql_data'	=> 'submit_post_modify_sql_data',
		);
	}

	/**
	* Add data to rowset
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_post_rowset_data($event)
	{
		$rowset_data = $event['rowset_data'];
		$row = $event['row'];
		$rowset_data = array_merge($rowset_data, array(
			'user_agent'			=> $row['user_agent'],
		));
		$event['rowset_data'] = $rowset_data;
	}

	/**
	* Add post row data.
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_post_row($event)
	{
		include_once $this->root_path . 'ext/dmzx/browsericon/includes/user_agent.' . $this->php_ext;
		$row = $event['row'];
		$post_row = $event['post_row'];
		$post_row = array_merge($post_row, array(
			'USER_AGENT' 			=> get_useragent_icons($row['user_agent']),	// USER AGENT
		));
		$event['post_row'] = $post_row;
	}

	/**
	* Adding sql data of posts
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function submit_post_modify_sql_data($event)
	{
		$post = $this->request->get_super_global(\phpbb\request\request::SERVER);
		$sql_data = $event['sql_data'];
		$sql_data[POSTS_TABLE]['sql']['user_agent'] =	$post['HTTP_USER_AGENT'];
		$event['sql_data'] = $sql_data;
	}
}