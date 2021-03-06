<?php pear::extends('_templates/orange_admin') ?>
<?php pear::section('section_container') ?>
<?=pear::open_multipart($controller_path, ['class'=>'form-horizontal','method'=>$form_method,'data-success'=>'Record Saved|blue'], ['id'=>$record->id]) ?>
	<div class="row">
		<div class="col-md-6"><h3><?=$ci_title_prefix ?> <?=$controller_title ?></h3></div>
	  <div class="col-md-6">
	  	<div class="pull-right">
				<?=pear::goback_button($controller_path) ?>
	  	</div>
	  </div>
	</div>
	<hr>
	<div class="form-group">
	  <div class="col-md-offset-3 col-md-4">
		  <h3>Administration View</h3>
	  </div>
	</div>

	<!-- Text input-->
	<div class="form-group">
		<?=pear::field_label('o_setting_model', 'group') ?>
		<div class="col-md-4">
			<?=pear::datalist('group', $record->group, $settings_group_catalog, ['class'=>'form-control']) ?>
		</div>
	</div>

	<!-- Text input-->
	<div class="form-group">
		<?=pear::field_label('o_setting_model', 'name') ?>
		<div class="col-md-4">
			<?=pear::input('name', $record->name, ['class'=>'form-control input-md','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- Text input-->
	<div class="form-group">
		<?=pear::field_label('o_setting_model', 'value') ?>
		<div class="col-md-4">
			<?=pear::input('value', $record->value, ['class'=>'form-control input-md','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- Text input-->
	<div class="form-group">
		<?=pear::field_label('o_setting_model', 'help') ?>
		<div class="col-md-4">
			<?=pear::input('help', $record->help, ['class'=>'form-control input-md','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checker('enabled', 1, $record->enabled) ?> Enabled
				</label>
			</div>
		</div>
	</div>

	<?=pear::include('_templates/access', ['record'=>$record]) ?>

	<!-- Text input-->
	<div class="form-group">
		<?=pear::field_label('o_setting_model', 'internal') ?>
		<div class="col-md-7">
			<?=pear::input('migration', $record->migration, ['readonly'=>'readonly','class'=>'form-control input-md']) ?>
		</div>
	</div>

	<!-- Text input-->
	<div class="form-group">
		<?=pear::label('Options', 'options', ['class'=>'col-md-3 control-label']) ?>
	  <div class="col-md-7">
			<?=pear::textarea('options', $record->options, ['class'=>'form-control fixed-font','cols'=>66,'rows'=>4]) ?>
			<h5>Example Options:</h5>
			<pre>{"type":"radio","options":{"1":"Red","2":"Green","3":"Yellow","4":"Blue"}}
{"type":"textarea","rows":5}
{"type":"checkbox","copy":"Show","data-on":8,"data-off":9}
{"type":"select","options":{"1":"Red","2":"Green","3":"Yellow","4":"Blue"}}
{"type":"text","width":"50","mask":"int"}</pre>
	  </div>
	</div>

	<!-- Submit Button -->
	<div class="form-group">
		<div class="col-md-12">
			<div class="pull-right">
				<?=pear::button(null, 'Save', ['class'=>'js-button-submit keymaster-s btn btn-primary']) ?>
			</div>
		</div>
	</div>
<?=pear::close() ?>

<?php pear::end() ?>
