<?php
/**
 * Google Index Shell
 *
 * Marc Grabanski just had the great idea of using google to help with 
 * the migration of your site to a new domain / url schema. Just get a 
 * list of all pages google has indexed from your site and then use that 
 * as your basis for checking if your migration worked or not. This is 
 * very convenient because you do not have to know all your own urls 
 * yourself, and you'll only get the relevant ones (if they are not in 
 * google they are unlikely to have traffic).
 *
 *
 * Google Index Shell : Crawl Google
 * Copyright 2009, Debuggable, Ltd. (http://debuggable.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2008, Debuggable, Ltd. (http://debuggable.com)
 * @link          http://debuggable.com/posts/crawl-google-they-do-the-same-to-you:484ebdeb-bbe8-45fa-ad0f-26b14834cda3
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class GoogleIndexShell extends Shell {
	function main() {
		App::import('HttpSocket');
		$site = (isset($this->args['0'])) ? $this->args['0'] : null;
		while (empty($site)) {
			$site = $this->in("What site would you like to crawl?");
			if (!empty($site)) break;
			$this->out("Try again.");
		}
		$Socket = new HttpSocket();
		$links = array();
		$start = 0;
		$num = 100;
		do {
			$r = $Socket->get('http://www.google.com/search', array(
				'hl' => 'en',
				'as_sitesearch' => $site,
				'num' => $num,
				'filter' => 0,
				'start' => $start,
			));
			if (!preg_match_all('/href="([^"]+)" class="?l"?/is', $r, $matches)) {
				die($this->out('Error: Could not parse google results'));
			}
			$links = array_merge($links, $matches[1]);
			$start = $start + $num;
		} while (count($matches[1]) >= $num);

		$links = array_unique($links);
		$this->out(sprintf('-> Found %d links on google:', count($links)));
		$this->hr();
		$this->out(join("\n", $links));
	}

/**
 * Help
 *
 * @return void
 * @access public
 */
	function help() {
		$this->out('Debuggable Ltd. Google Index Shell - http://debuggable.com');
		$this->hr();
		$this->out('Crawl Google, they do the same to you ;)');
		$this->out('');
		$this->hr();
		$this->out("Usage: cake google_index example.com");
		$this->out('');
	}
}
?>