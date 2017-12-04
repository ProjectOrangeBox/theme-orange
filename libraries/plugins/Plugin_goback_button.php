<?php 

class Plugin_goback_button {

	public function __construct() {
		plugin::attach('goback_button',function($uri='',$title='Go Back',$attributes=[]) {
			$default_attributes = ['class'=>'btn btn-default btn-sm js-esc'];

			$attributes = array_merge($default_attributes,(array)$attributes);

			return anchor($uri,'<i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> '.$title,$attributes);
		});
	}

} /* end class */