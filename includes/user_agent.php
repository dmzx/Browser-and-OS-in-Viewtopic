<?php
/**
*
* @package phpBB Extension - Browser & OS in Viewtopic
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
if ( !defined('IN_PHPBB') )
{
	exit;
}
/*
	Returns relative path to icon
*/
function ua_get_filename($name, $folder)
{
	if (substr($name, 0, 11) == 'unknown')
		return 'ext/dmzx/browsericon/images/user_agent/unknown.png';

	$name = strtolower($name);
	$name = str_replace(' ', '', $name); // remove spaces
	$name = preg_replace('/[^a-z0-9_]/', '', $name); // remove special characters
	return 'ext/dmzx/browsericon/images/user_agent/'.$folder.'/'.$name.'.png';
}

/*
	Returns first found element in $useragent from $items array
*/
function ua_search_for_item($items, $useragent)
{
	$result = '';

	foreach ($items as $item)
	{
		if (strpos($useragent, strtolower($item)) !== false)
		{
			$result = $item;
			break;
		}
	}
	return $result;
}

/*
	Main function detecting browser and system
*/
function get_useragent_names($useragent)
{
	if (!$useragent)
	{
		$result = array(
			'system'			=> 'unknown',
			'browser'			=> 'unknown',
			'browser_version'	=> ''
		);
		return $result;
	}

	$useragent = strtolower($useragent);

	// Browser detection
	$browsers = array('Edge', 'AWeb', 'Camino', 'Epiphany', 'Galeon', 'HotJava', 'iCab', 'MSIE', 'Chrome', 'Safari',	'Konqueror', 'Flock', 'Iceweasel', 'SeaMonkey', 'Firefox', 'Firebird', 'Netscape', 'Mozilla', 'Opera', 'Maxthon', 'PhaseOut', 'SlimBrowser');

	$browser = ua_search_for_item($browsers, $useragent);

	$browser_version = '';
	if ($browser != '')
	{
		if ($browser == 'Opera' && strpos($useragent, 'version') !== false)
			$browser_version = substr($useragent, strpos($useragent, 'version') + 8);
		else
			$browser_version = substr($useragent, strpos($useragent, strtolower($browser)) + strlen($browser) + 1);

		if (preg_match('/([0-9\.]*)/', $browser_version, $matches))
			$browser_version = $matches[1];
	}

	// Detect IE version
	if ($browser == 'MSIE')
	{
		$browser = 'Internet Explorer';
		if ($browser_version != '')
		{
			$ver = substr($browser_version, 0, 1);

			if ($ver >= 6)
			{
				$browser .= ' '. $ver;
				$browser_version = '';
			}
		}
	}
	// Edge to Spartan
	if ($browser == 'Edge')
	{
		$browser = 'Spartan';
	}

	// System detection
	$systems = array('Android', 'iPhone', 'Windows Phone', 'Amiga', 'BeOS', 'FreeBSD', 'HP-UX', 'Linux', 'NetBSD', 'OS/2', 'SunOS', 'Symbian', 'Unix', 'Windows', 'Sun', 'Macintosh', 'Mac');

	$system = ua_search_for_item($systems, $useragent);

	if ($system == 'Linux')
	{
		$systems = array('CentOS', 'Debian', 'Fedora', 'Freespire', 'Gentoo', 'Katonix', 'KateOS', 'Knoppix', 'Kubuntu', 'Linspire', 'Mandriva', 'Mandrake', 'RedHat', 'Slackware', 'Slax', 'Suse', 'Xubuntu', 'Ubuntu', 'Xandros', 'Arch', 'Ark');

		$system = ua_search_for_item($systems, $useragent);
		if ($system == '')
			$system = 'Linux';

		if ($system == 'Mandrake')
			$system = 'Mandriva';
	}
	elseif ($system == 'Windows')
	{
		$version = substr($useragent, strpos($useragent, 'windows nt ') + 11);
		if (substr($version, 0, 3) == 5.1)
			$system = 'Windows XP';
		elseif (substr($version, 0, 1) == 6)
		{
			if (substr($version, 0, 3) == 6.0)
				$system = 'Windows Vista';

			elseif (substr($version, 0, 3) == 6.1)
				$system = 'Windows 7';

			elseif (substr($version, 0, 3) == 6.2)
				$system = 'Windows 8';

			elseif (substr($version, 0, 3) == 6.3)
				$system = 'Windows 8.1';
		}
		elseif (substr($version, 0, 3) == 10)
		{
			if (substr($version, 0, 3) == 10.0)
				$system = 'Windows 10';
		}
	}
	elseif ($system == 'Mac')
	{
		$system = 'Macintosh';
	}
	elseif ($system == 'Android')
	{
		$system = 'Android';
	}
	elseif ($system == 'iPhone')
	{
		$system = 'iPhone';
	}
	elseif ($system == 'Windows Phone')
			$system = 'Windows_Phone';

	if (!$system)
		$system = 'unknown';
	if (!$browser)
		$browser = 'unknown';

	$result = array(
		'system'			=> $system,
		'browser'			=> $browser,
		'browser_version'	=> $browser_version
	);

	return $result;
}

/*
	Displays icons
*/
function get_useragent_icons($useragent)
{
	$agent = get_useragent_names($useragent);

	$result = '<img src="'.ua_get_filename($agent['system'], 'os').'" style="cursor: pointer" title="'.utf8_htmlspecialchars($agent['system']).'" alt="'.utf8_htmlspecialchars($agent['system']).'" /> <img src="'.ua_get_filename($agent['browser'], 'browser').'" style="cursor: pointer" title="'.utf8_htmlspecialchars($agent['browser'].' '.$agent['browser_version']).'" alt="'.utf8_htmlspecialchars($agent['browser']).'" /><br />';

	$description = addslashes($useragent) . '\n\nComputer System :\t\t ' . addslashes($agent['system']);
	$description .= '\nBrowser : \t' . addslashes($agent['browser'].' '.$agent['browser_version']);

	return '<span class="user-agent" onclick="alert(\'' . utf8_htmlspecialchars($description) . '\')">' . $result . '</span>';
}
