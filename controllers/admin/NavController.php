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
*/

class NavController extends \MY_Controller
{
	use admin_controller_trait;

	public $controller        = 'nav';
	public $controller_path   = '/admin/nav';
	public $controller_model  = 'o_nav_model';
	public $controller_title  = 'Menu';
	public $controller_titles = 'Menus';
	public $controller_order_by = 'sort';

	public function sortAction() : void
	{
		ci('page')->data(['list'=>ci('nav_sort_library')->create_list(ci('o_nav_model')->get_all(), config('nav.dd-list'))])->render();
	}

	public function sortPostAction() : void
	{
		ci('nav_sort_library')->process_tree_sort(ci('input')->request('tree'), 1);

		ci('output')->json('html', 'Updated');
	}

	public function detailsAction(string $id = null) : void
	{
		$nav_options[1] = '~ Root ~';

		$records = ci('o_nav_model')->catalog('id', '*', null, 'text');

		foreach ($records as $record) {
			$text = (empty($record['url'])) ? $record['text'] : $record['text'].' ~ '.$record['url'];

			$nav_options[$record['id']] = $text;
		}

		ci('page')->data('nav_options', $nav_options);

		$data = ($id) ? $this->_edit_record($id) : $this->_new_record();

		ci('page')->render(null, (array)$data);
	}
} /* end controller */
