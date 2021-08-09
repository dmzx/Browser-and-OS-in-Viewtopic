<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\browsericon\event;

use dmzx\browsericon\core\functions;
use phpbb\request\request;
use phpbb\language\language;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var functions */
	protected $functions;

	/** @var request */
	protected $request;

	/**
	 * Constructor
	 * @param functions			$functions
	 * @param request			$request
	 */
	public function __construct(
		functions $functions,
		request $request,
		$phpbb_root_path,
		$php_ext
	)
	{
		$this->functions = $functions;
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
		return [
			'core.viewtopic_post_rowset_data'	=> 'viewtopic_post_rowset_data',
			'core.viewtopic_modify_post_row'	=> 'viewtopic_modify_post_row',
			'core.submit_post_modify_sql_data'	=> 'submit_post_modify_sql_data',
		];
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
		$rowset_data = array_merge($rowset_data, [
			'user_agent' => $row['user_agent'],
		]);
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
		$row = $event['row'];
		$post_row = $event['post_row'];

		$this->functions->setUserAgent($row['user_agent']);
		$browser = $this->functions->getBrowser();
		$brows = str_replace(' ', '', strtolower($browser)); // remove spaces
		$brows = preg_replace('/[^a-z0-9_]/', '', $brows); // remove special characters
		$browser_version =	$this->functions->getVersion();
		$browser_name = $this->functions->getName();

		if ($browser_name !== 'unknown')
		{
			$browser = $browser_name;
			$browser_version = '';
		}

		$browser_image = '<img src="ext/dmzx/browsericon/styles/prosilver/theme/images/browsers/' . $brows . '.png" alt="' . $browser . '" style="cursor: pointer" title="' . $browser . ' ' . $browser_version . '" />';

		$platform = $this->functions->getPlatform();
		$plat = str_replace(' ', '', strtolower($platform)); // remove spaces
		$plat = preg_replace('/[^a-z0-9_]/', '', $plat); // remove special characters
		$platform_image = '<img src="ext/dmzx/browsericon/styles/prosilver/theme/images/os/' . $plat . '.png" alt="' . $platform . '" style="cursor: pointer" title="' . $platform . '"/>';

		$post_row = array_merge($post_row, [
			'USER_AGENT' => $platform_image . ' ' . $browser_image,
		]);
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
		$post = $this->request->server('HTTP_USER_AGENT');
		$sql_data = $event['sql_data'];
		if (($event['post_mode'] == 'post') || ($event['post_mode'] == 'reply'))
		{
			$sql_data[POSTS_TABLE]['sql']['user_agent'] = $post;
		}
		$event['sql_data'] = $sql_data;
	}
}
