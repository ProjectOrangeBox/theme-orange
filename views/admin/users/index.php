<? page::extends('_templates/orange_admin') ?>

<? page::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><?=plugin::title($controller_titles,'user') ?></div>
  <div class="col-md-6">
  	<div class="pull-right">
  		<?=plugin::search_sort_field() ?>
			<?=plugin::new_button($controller_path.'/details','New '.$controller_title) ?>
  	</div>
  </div>
</div>

<div class="row">
	<table class="table orange sortable table-hover">
			<thead>
				<tr class="panel-default">
					<th class="panel-heading">Username</th>
					<th class="panel-heading">Email</th>
					<th class="panel-heading text-center">Active</th>
					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
		<? foreach ($records as $row) { ?>
			<? if (user::has_role($row->read_role_id)) { ?>
				<tr>
					<td><?=e($row->username) ?></td>
					<td><?=e($row->email) ?></td>
					<td class="text-center"><?=plugin::fa_enum_icon($row->is_active) ?></td>
					<td class="text-center actions">
						<? if (user::has_role($row->edit_role_id)) { ?>
							<?=plugin::edit_button($controller_path.'/details/'.$row->id) ?>
						<? } ?>
						<? if (user::has_role($row->delete_role_id)) { ?>
							<?=plugin::delete_button($controller_path,['id'=>$row->id]) ?>
						<? } ?>
					</td>
				</tr>
			<? } ?>
		<? } ?>
		</tbody>
	</table>
</div>

<? page::end() ?>
