<?php

class Plugin_rest_form {

	public function __construct() {
		ci()->page->js('/theme/orange/assets/plugins/rest-form/rest-form.min.js');
	}

} /* end class */