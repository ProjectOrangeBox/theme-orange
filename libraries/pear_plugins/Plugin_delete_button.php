<?php

class Plugin_delete_button {

	public function __construct() {
		pear::attach('delete_button',function($uri='',$attributes=[]) {
			$name = ($attributes['primary_key']) ? $attributes['primary_key'] : 'id';
			
			$html  = '<form action="'.$uri.'" method="delete" data-confirm="true" data-fadeout="tr">';
			$html .= '<input type="hidden" name="'.$name.'" value="'.$attributes['id'].'">';
			$html .= '<a href="#" class="js-button-submit">';
			$html .= '<i class="fa fa-trash fa-lg" aria-hidden="true">';
			$html .= '</i>';
			$html .= '</a>';
			$html .= '</form>';
		
			return $html;
		});
	}

} /* end class */
