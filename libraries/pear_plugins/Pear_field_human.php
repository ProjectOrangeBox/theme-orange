<?php

class Pear_field_human
{
	public function render($model=null, $field=null)
	{
		$rule = (class_exists($model, false)) ? ci($model)->rule($field) : [];

		return (empty($rule['label'])) ? ucwords(strtolower($field)) : $rule['label'];
	}
}
