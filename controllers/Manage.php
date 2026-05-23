<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODPATH.'core/libraries/Nova_controller_admin.php';

class __extensions__nova_ext_ordered_mission_posts__Manage extends Nova_controller_admin
{
	private $required_post_columns = array(
		'nova_ext_ordered_post_day',
		'nova_ext_ordered_post_time',
		'nova_ext_ordered_post_date',
		'nova_ext_ordered_post_stardate',
	);

	private $required_mission_columns = array(
		'mission_ext_ordered_config_setting',
		'mission_ext_ordered_post_numbering',
		'mission_ext_ordered_default_mission_date',
		'mission_ext_ordered_default_stardate',
		'mission_ext_ordered_legacy_mode',
		'mission_ext_ordered_is_new_record',
	);

	public function __construct()
	{
		parent::__construct();

		$this->ci =& get_instance();
		$this->_regions['nav_sub'] = Menu::build('adminsub', 'manageext');
	}

	public function config()
	{
		Auth::check_access('site/settings');

		$configPath = dirname(__FILE__).'/../config.json';

		// --- handle POST actions ---
		$action = isset($_POST['action']) ? $_POST['action'] : '';

		if ($action === 'setup_database') {
			$this->_flash($this->_setupDatabase());
		} elseif ($action === 'install_email') {
			$this->_flash($this->_writeControllerBlock('email'));
		} elseif ($action === 'install_feed') {
			$this->_flash($this->_writeControllerBlock('feed'));
		} elseif ($action === 'save_labels') {
			$this->_flash($this->_saveLabels($configPath));
		} elseif ($action === 'save_legacy') {
			$this->_flash($this->_saveLegacy($configPath));
		}

		// --- build view data ---
		$data = array();
		$data['title'] = 'Ordered Mission Posts - Configuration';
		$data['jsons'] = json_decode(file_get_contents($configPath), true);

		$data['missing_columns'] = $this->_missingColumns();
		$data['missing_indexes'] = $this->_missingIndexes();
		$data['db_ready'] = empty($data['missing_columns']['posts'])
			&& empty($data['missing_columns']['missions'])
			&& empty($data['missing_indexes']);

		$data['email_state'] = $this->_controllerBlockState('email');
		$data['feed_state']  = $this->_controllerBlockState('feed');

		$postFields = $this->db->list_fields($this->db->dbprefix.'posts');
		$data['legacy_available'] = in_array('post_chronological_mission_post_day', $postFields);
		$data['legacy_enabled'] = isset($data['jsons']['setting']['legacy_mode'])
			&& $data['jsons']['setting']['legacy_mode'] == 1;

		$this->_regions['title'] .= 'Configuration';
		$this->_regions['content'] = $this->extension['nova_ext_ordered_mission_posts']
			->view('config', $this->skin, 'admin', $data);

		Template::assign($this->_regions);
		Template::render();
	}

	// ---------- helpers ----------

	private function _flash($result)
	{
		$flash = array(
			'status'  => ($result[0] === 'error') ? 'error' : 'success',
			'message' => text_output($result[1]),
		);

		$this->_regions['flash_message'] = Location::view('flash', $this->skin, 'admin', $flash);
	}

	private function _missingColumns()
	{
		$prefix = $this->db->dbprefix;
		$missing = array('posts' => array(), 'missions' => array());

		$postFields = $this->db->list_fields($prefix.'posts');
		foreach ($this->required_post_columns as $col) {
			if ( ! in_array($col, $postFields)) {
				$missing['posts'][] = $col;
			}
		}

		$missionFields = $this->db->list_fields($prefix.'missions');
		foreach ($this->required_mission_columns as $col) {
			if ( ! in_array($col, $missionFields)) {
				$missing['missions'][] = $col;
			}
		}

		return $missing;
	}

	private function _missingIndexes()
	{
		$prefix = $this->db->dbprefix;
		$missing = array();

		if ( ! $this->_indexExists($prefix.'posts', 'post_ordered_mission_post')) {
			$missing[] = 'post_ordered_mission_post';
		}
		if ( ! $this->_indexExists($prefix.'missions', 'post_ordered_mission')) {
			$missing[] = 'post_ordered_mission';
		}

		return $missing;
	}

	private function _indexExists($table, $indexName)
	{
		$result = $this->db->query('SHOW INDEX FROM `'.$table.'`');
		foreach ($result->result() as $row) {
			if ($row->Key_name === $indexName) {
				return true;
			}
		}
		return false;
	}

	private function _columnSql($table, $column)
	{
		$defs = array(
			'nova_ext_ordered_post_day'                => 'INTEGER NOT NULL DEFAULT 1',
			'nova_ext_ordered_post_time'               => "VARCHAR(4) NOT NULL DEFAULT '0000'",
			'nova_ext_ordered_post_date'               => 'VARCHAR(255) DEFAULT NULL',
			'nova_ext_ordered_post_stardate'           => 'VARCHAR(255) DEFAULT NULL',
			'mission_ext_ordered_config_setting'       => 'VARCHAR(255) DEFAULT NULL',
			'mission_ext_ordered_post_numbering'       => 'INTEGER NOT NULL DEFAULT 0',
			'mission_ext_ordered_default_mission_date' => 'VARCHAR(255) DEFAULT NULL',
			'mission_ext_ordered_default_stardate'     => 'VARCHAR(255) DEFAULT NULL',
			'mission_ext_ordered_legacy_mode'          => 'INTEGER NOT NULL DEFAULT 0',
			'mission_ext_ordered_is_new_record'        => 'INTEGER DEFAULT 0',
		);

		if ( ! isset($defs[$column])) {
			return '';
		}

		return 'ALTER TABLE `'.$this->db->dbprefix.$table.'` ADD COLUMN `'.$column.'` '.$defs[$column];
	}

	private function _setupDatabase()
	{
		$missing = $this->_missingColumns();

		$columnsAdded = 0;
		foreach (array('posts', 'missions') as $table) {
			foreach ($missing[$table] as $column) {
				$sql = $this->_columnSql($table, $column);
				if ($sql !== '') {
					$this->db->query($sql);
					$columnsAdded++;
				}
			}
		}

		$prefix = $this->db->dbprefix;
		$indexesAdded = 0;
		foreach ($this->_missingIndexes() as $index) {
			if ($index === 'post_ordered_mission_post') {
				$this->db->query(
					'CREATE INDEX `post_ordered_mission_post` ON `'.$prefix.'posts` '
					.'(`nova_ext_ordered_post_day`, `nova_ext_ordered_post_date`, `nova_ext_ordered_post_stardate`, `nova_ext_ordered_post_time`)'
				);
				$indexesAdded++;
			} elseif ($index === 'post_ordered_mission') {
				$this->db->query(
					'CREATE INDEX `post_ordered_mission` ON `'.$prefix.'missions` '
					.'(`mission_ext_ordered_config_setting`, `mission_ext_ordered_post_numbering`, `mission_ext_ordered_default_mission_date`, `mission_ext_ordered_default_stardate`, `mission_ext_ordered_legacy_mode`, `mission_ext_ordered_is_new_record`)'
				);
				$indexesAdded++;
			}
		}

		if ($columnsAdded === 0 && $indexesAdded === 0) {
			return array('success', 'Database is already fully set up - nothing to add.');
		}

		return array('success', 'Database setup complete. Added '.$columnsAdded.' column(s) and '.$indexesAdded.' index(es).');
	}

	private function _saveLabels($configPath)
	{
		$json = json_decode(file_get_contents($configPath), true);

		foreach ($json['nova_ext_ordered_mission_posts'] as $key => $field) {
			if (isset($_POST[$key])) {
				$json['nova_ext_ordered_mission_posts'][$key]['value'] = $_POST[$key];
			}
		}

		file_put_contents($configPath, json_encode($json, JSON_PRETTY_PRINT));

		return array('success', 'Labels updated.');
	}

	private function _saveLegacy($configPath)
	{
		$json = json_decode(file_get_contents($configPath), true);
		$json['setting']['legacy_mode'] = isset($_POST['legacy_mode']) ? $_POST['legacy_mode'] : 0;

		file_put_contents($configPath, json_encode($json, JSON_PRETTY_PRINT));

		return array('success', 'Legacy mode setting saved.');
	}

	// ---------- controller-block writer ----------

	private function _blockMap()
	{
		return array(
			'email' => array(
				'file'   => APPPATH.'controllers/Write.php',
				'txt'    => dirname(__FILE__).'/../write.txt',
				'tag'    => 'email',
				'method' => '_email',
				'label'  => 'Post email controller code',
			),
			'feed' => array(
				'file'   => APPPATH.'controllers/Feed.php',
				'txt'    => dirname(__FILE__).'/../feed.txt',
				'tag'    => 'feed',
				'method' => 'posts',
				'label'  => 'Feed controller code',
			),
		);
	}

	private function _controllerBlockState($which)
	{
		$map = $this->_blockMap();
		if ( ! isset($map[$which])) {
			return 'unknown';
		}
		$m = $map[$which];

		if ( ! file_exists($m['file'])) {
			return 'missing_file';
		}

		$file = file_get_contents($m['file']);
		$txt  = file_exists($m['txt']) ? file_get_contents($m['txt']) : '';

		$installedVersion = $this->_blockVersion($file, $m['tag']);
		$currentVersion   = $this->_blockVersion($txt,  $m['tag']);

		if ($installedVersion !== null) {
			return ($installedVersion === $currentVersion) ? 'current' : 'outdated';
		}

		if (preg_match('/function\s+'.preg_quote($m['method'], '/').'\s*\(/', $file)) {
			return 'legacy';
		}

		return 'missing';
	}

	private function _blockVersion($content, $tag)
	{
		if (preg_match('/nova_ext_ordered_mission_posts:'.preg_quote($tag, '/').' v(\d+) START/', $content, $match)) {
			return (int) $match[1];
		}
		return null;
	}

	private function _writeControllerBlock($which)
	{
		$map = $this->_blockMap();
		if ( ! isset($map[$which])) {
			return array('error', 'Unknown block.');
		}
		$m = $map[$which];

		$state = $this->_controllerBlockState($which);

		if ($state === 'current') {
			return array('success', $m['label'].' is already up to date.');
		}
		if ($state === 'missing_file') {
			return array('error', 'Could not find '.$m['file'].'.');
		}
		$file = file_get_contents($m['file']);
		if ( ! file_exists($m['txt'])) {
			return array('error', 'Cannot find '.basename($m['txt']).' in the extension.');
		}
		$block = rtrim(file_get_contents($m['txt']), "\r\n");

		if ($state === 'outdated') {
			$pattern = '/[ \t]*\/\*\s*nova_ext_ordered_mission_posts:'.preg_quote($m['tag'], '/')
				.' v\d+ START.*?nova_ext_ordered_mission_posts:'.preg_quote($m['tag'], '/').' END\s*\*\//s';
			$new = preg_replace($pattern, $block, $file, 1, $count);
			if ($count !== 1) {
				return array('error', 'Could not locate the managed block in '.basename($m['file']).'. Update by hand per the README.');
			}
			$file = $new;
		} elseif ($state === 'legacy') {
			$span = $this->_findUnmarkedMethodSpan($file, $m['method']);
			if ($span === null) {
				return array('error', 'Could not parse the existing '.$m['method'].'() method in '.basename($m['file']).'. Update by hand per the README.');
			}
			$file = substr($file, 0, $span[0]).$block."\n".substr($file, $span[1]);
		} else {
			// state === 'missing' - insert before the class's final closing brace
			$pos = strrpos($file, '}');
			if ($pos === false) {
				return array('error', basename($m['file']).' is not in the expected format. Install by hand per the README.');
			}
			$file = rtrim(substr($file, 0, $pos))."\n\n".$block."\n}\n";
		}

		file_put_contents($m['file'], $file);

		return array('success', $m['label'].' updated successfully.');
	}

	/**
	 * Locate the byte span of an unmarked $methodName declaration in $content.
	 * Returns array($start, $end) (end exclusive, includes the trailing newline
	 * if present), or null if the method can't be cleanly located. A minimal
	 * lexer is used so that braces, comments, and string literals don't fool
	 * the counter.
	 */
	private function _findUnmarkedMethodSpan($content, $methodName)
	{
		$len = strlen($content);
		$state = 'normal';
		$functionPositions = array();
		$i = 0;

		// First pass: collect offsets of the `function` keyword that fall
		// outside any string or comment.
		while ($i < $len) {
			$c = $content[$i];
			$next = ($i + 1 < $len) ? $content[$i + 1] : '';

			if ($state === 'normal') {
				if ($c === "'") { $state = 'single'; $i++; continue; }
				if ($c === '"') { $state = 'double'; $i++; continue; }
				if ($c === '/' && $next === '/') { $state = 'line_comment'; $i += 2; continue; }
				if ($c === '/' && $next === '*') { $state = 'block_comment'; $i += 2; continue; }
				if ($c === 'f'
					&& substr($content, $i, 8) === 'function'
					&& ($i === 0 || ! self::_isIdentChar($content[$i - 1]))
					&& ($i + 8 >= $len || ! self::_isIdentChar($content[$i + 8]))) {
					$functionPositions[] = $i;
					$i += 8;
					continue;
				}
			} elseif ($state === 'single') {
				if ($c === '\\') { $i += 2; continue; }
				if ($c === "'") $state = 'normal';
			} elseif ($state === 'double') {
				if ($c === '\\') { $i += 2; continue; }
				if ($c === '"') $state = 'normal';
			} elseif ($state === 'line_comment') {
				if ($c === "\n") $state = 'normal';
			} elseif ($state === 'block_comment') {
				if ($c === '*' && $next === '/') { $state = 'normal'; $i += 2; continue; }
			}
			$i++;
		}

		foreach ($functionPositions as $fnPos) {
			$p = $fnPos + 8;
			while ($p < $len && ctype_space($content[$p])) {
				$p++;
			}
			$nameLen = strlen($methodName);
			if ($p + $nameLen > $len) continue;
			if (substr($content, $p, $nameLen) !== $methodName) continue;
			if ($p + $nameLen < $len && self::_isIdentChar($content[$p + $nameLen])) continue;

			// Walk back through whitespace + visibility modifiers to find the
			// declaration's true start.
			$k = $fnPos - 1;
			while ($k >= 0 && ($content[$k] === ' ' || $content[$k] === "\t")) {
				$k--;
			}
			foreach (array('static', 'final', 'abstract', 'protected', 'public', 'private') as $kw) {
				$klen = strlen($kw);
				if ($k - $klen + 1 >= 0
					&& substr($content, $k - $klen + 1, $klen) === $kw
					&& ($k - $klen < 0 || ! self::_isIdentChar($content[$k - $klen]))) {
					$k -= $klen;
					while ($k >= 0 && ($content[$k] === ' ' || $content[$k] === "\t")) {
						$k--;
					}
				}
			}
			$start = $k + 1;

			// Walk forward, counting braces (skipping strings/comments) until
			// the method's matching close brace is found.
			$q = $p + $nameLen;
			$bs = 'normal';
			$depth = 0;
			$started = false;
			while ($q < $len) {
				$c = $content[$q];
				$next = ($q + 1 < $len) ? $content[$q + 1] : '';
				if ($bs === 'normal') {
					if ($c === '{') {
						$depth++;
						$started = true;
					} elseif ($c === '}') {
						$depth--;
						if ($started && $depth === 0) {
							$end = $q + 1;
							if ($end < $len && $content[$end] === "\n") $end++;
							return array($start, $end);
						}
					} elseif ($c === "'") { $bs = 'single'; $q++; continue; }
					elseif ($c === '"') { $bs = 'double'; $q++; continue; }
					elseif ($c === '/' && $next === '/') { $bs = 'line_comment'; $q += 2; continue; }
					elseif ($c === '/' && $next === '*') { $bs = 'block_comment'; $q += 2; continue; }
				} elseif ($bs === 'single') {
					if ($c === '\\') { $q += 2; continue; }
					if ($c === "'") $bs = 'normal';
				} elseif ($bs === 'double') {
					if ($c === '\\') { $q += 2; continue; }
					if ($c === '"') $bs = 'normal';
				} elseif ($bs === 'line_comment') {
					if ($c === "\n") $bs = 'normal';
				} elseif ($bs === 'block_comment') {
					if ($c === '*' && $next === '/') { $bs = 'normal'; $q += 2; continue; }
				}
				$q++;
			}
			return null;
		}

		return null;
	}

	private static function _isIdentChar($ch)
	{
		return ctype_alnum($ch) || $ch === '_';
	}
}
