<?php
/*
 * Orange Framework Extension
 *
 * @package	CodeIgniter / Orange
 * @author Don Myers
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ProjectOrangeBox
 *
 */

class Pear_form_static {
	public function __construct() {
		pear::attach('form_static',function($string) {
			return '<p class="form-control-static">'.htmlspecialchars($string, ENT_QUOTES, 'UTF-8').'</p>';
		});
	}
}