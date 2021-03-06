<?php
/**
 * Orange Framework Extension
 *
 * This content is released under the MIT License (MIT)
 *
 * @package	CodeIgniter / Orange
 * @author	Don Myers
 * @license http://opensource.org/licenses/MIT MIT License
 * @link	https://github.com/ProjectOrangeBox
 *
CREATE TABLE `orange_nav` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`created_on` datetime DEFAULT current_timestamp(),
	`created_by` int(11) unsigned NOT NULL DEFAULT 1,
	`created_ip` varchar(15) DEFAULT 'NULL',
	`updated_on` datetime DEFAULT current_timestamp(),
	`updated_by` int(11) unsigned NOT NULL DEFAULT 1,
	`updated_ip` varchar(15) DEFAULT 'NULL',
	`access` int(10) unsigned DEFAULT 0,
	`url` varchar(255) NOT NULL DEFAULT '',
	`text` varchar(255) NOT NULL DEFAULT '',
	`parent_id` int(11) unsigned NOT NULL DEFAULT 0,
	`sort` int(11) unsigned NOT NULL DEFAULT 0,
	`target` varchar(128) DEFAULT NULL,
	`class` varchar(32) DEFAULT '',
	`active` tinyint(1) unsigned NOT NULL DEFAULT 1,
	`color` varchar(7) NOT NULL DEFAULT 'd28445',
	`icon` varchar(32) NOT NULL DEFAULT 'square',
	`read_role_id` int(10) unsigned DEFAULT 0,
	`edit_role_id` int(10) unsigned DEFAULT 0,
	`delete_role_id` int(10) unsigned DEFAULT 0,
	`migration` varchar(128) DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_parent_id` (`parent_id`) USING BTREE,
	KEY `idx_access` (`access`) USING BTREE,
	KEY `idx_active` (`active`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * required
 * core: session, load, input
 * libraries: event
 * models:
 * helpers:
 * functions: setting
 *
 */
class O_nav_model extends \Database_model
{
	protected $table = 'orange_nav';
	protected $rules = [
		'id'           => ['field' => 'id', 'label' => 'Id', 'rules' => 'required|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'url'          => ['field' => 'url', 'label' => 'URL', 'rules' => 'filter_uri[255]|rtrim[/]|max_length[255]|filter_input[255]|strtolower'],
		'text'         => ['field' => 'text', 'label' => 'Text', 'rules' => 'required|max_length[255]|filter_input[255]'],
		'parent_id'    => ['field' => 'parent_id', 'label' => 'Parent Id', 'rules' => 'if_empty[0]|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'sort'         => ['field' => 'sort', 'label' => 'Sort', 'rules' => 'if_empty[0]|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'access'       => ['field' => 'access', 'label' => 'Permission', 'rules' => 'required|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'class'        => ['field' => 'class', 'label' => 'Class', 'rules' => 'filter_input[32]'],
		'active'       => ['field' => 'active', 'label' => 'Active', 'rules' => 'if_empty[0]|in_list[0,1]|filter_int[1]|max_length[1]|less_than[2]'],
		'color'        => ['field' => 'color', 'label' => 'Color', 'rules' => 'if_empty[d28445]|filter_hex[6]|max_length[6]|filter_input[6]'],
		'icon'         => ['field' => 'icon', 'label' => 'Icon', 'rules' => 'if_empty[square]|max_length[32]|filter_input[32]'],
		'target'       => ['field' => 'target', 'label' => 'Target', 'rules' => 'filter_input[128]'],
		'migration'   => ['field' => 'migration', 'label' => 'Migration', 'rules' => 'max_length[255]'],
	];
	protected $has = [
		'read_role'=>'read_role_id',
		'edit_role'=>'edit_role_id',
		'delete_role'=>'delete_role_id',
		'created_by'=>'created_by',
		'created_on'=>'created_on',
		'created_ip'=>'created_ip',
		'updated_by'=>'updated_by',
		'updated_on'=>'updated_on',
		'updated_ip'=>'updated_ip',
	];
	protected $order_by = 'url sort';

	public function get_all()
	{
		/* starting at root (1) get all */
		return $this->_children(1, false, 1, false, false);
	}

	public function get_filtered($parent_id, $access)
	{
		/* merge 0 (everyone) and the rest of your permissions */
		$access = (is_array($access)) ? array_merge([0], $access) : [0];

		/* the cache key based on the menu parent id & permissions */
		$key = $this->cache_prefix.'.get_filtered.'.md5(json_encode(func_get_args()));

		/* is this cached? */
		if (!$cache = $this->cache->get($key)) {
			/* no - therefore we need to create the cache */
			$cache = $this->_children($parent_id, $access, 1, true, true);

			/* save the cache */
			$this->cache->save($key, $cache, ci('cache')->ttl());
		}

		/* return the cached array */
		return $cache;
	}

	public function get_unfiltered($parent_id)
	{
		/* the cache key based on the menu parent id & permissions */
		$key = $this->cache_prefix.'.get_unfiltered.'.$parent_id;

		/* is this cached? */
		if (!$cache = $this->cache->get($key)) {
			/* no - therefore we need to create the cache */
			$cache = $this->_children($parent_id, false, 1, false, true);

			/* save the cache */
			$this->cache->save($key, $cache, ci('cache')->ttl());
		}

		/* return the cached array */
		return $cache;
	}

	protected function _children($parent_id, $access, $level, $remove_empty_parents, $active)
	{
		$array = false;

		if ($access) {
			$this->where_in('access', $access);
		}

		$where_clause = ($active) ? ['parent_id'=>$parent_id,'active'=>1] : ['parent_id'=>$parent_id];

		$records = $this->as_array()->where($where_clause)->order_by('sort')->get_many();

		foreach ($records as $record) {
			if ($children = $this->_children($record['id'], $access, ($level + 1), $remove_empty_parents, $active)) {
				$record['children'] = $children;
			}

			$record['level'] = $level;

			if ($remove_empty_parents) {
				if (!(empty($record['url']) && !is_array($record['children']))) {
					$array[$record['id']] = $record;
				}
			} else {
				$array[$record['id']] = $record;
			}
		}

		return $array;
	}

	/* migration */
	public function migration_add(string $url,string $text,string $migration,array $optional=[])
	{
		$this->skip_rules = true;

		$columns = [
			'read_role_id'=>ADMIN_ROLE_ID,
			'edit_role_id'=>ADMIN_ROLE_ID,
			'delete_role_id'=>ADMIN_ROLE_ID,
			'created_on'=>date('Y-m-d H:i:s'),
			'created_by'=>0,
			'created_ip'=>'0.0.0.0',
			'updated_on'=>date('Y-m-d H:i:s'),
			'updated_by'=>0,
			'updated_ip'=>'0.0.0.0',
			'parent_id'=>1,
			'url'=>$url,
			'text'=>$text,
			'migration'=>$migration,
			'icon'=>'square',
			'color'=>'d28445',
			'access'=>0,
			'active'=>0,
		];

		foreach ($optional as $key=>$val) {
			$columns[$key] = $val;
		}

		/* we already verified the key that's the "real" primary key */
		return (!$this->exists(['url'=>$url,'text'=>$text])) ? $this->insert($columns) : false;
	}

	public function migration_remove(string $migration) : bool
	{
		$this->skip_rules = true;

		unset($this->has['delete_role']);

		return $this->delete_by(['migration'=>$migration]);
	}
} /* end class */
