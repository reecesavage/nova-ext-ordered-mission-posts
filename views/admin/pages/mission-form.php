<p>
	<kbd><?php echo $label['mission_ext_ordered_config_setting'] ?></kbd>
	<?php echo form_dropdown($inputs['mission_ext_ordered_config_setting'],$option['mission_ext_ordered_config_setting'],$value['mission_ext_ordered_config_setting'],$configId['mission_ext_ordered_config_setting']) ?>
</p>


<p>
	<kbd><?php echo $label['mission_ext_ordered_post_numbering'] ?></kbd>
	<?php echo form_checkbox($inputs['mission_ext_ordered_post_numbering'], $value['mission_ext_ordered_post_numbering'], $checked['mission_ext_ordered_post_numbering']); ?>
</p>

<p>
	<kbd><?php echo $label['mission_ext_ordered_default_mission_date'] ?></kbd>
	<?php echo form_input($inputs['mission_ext_ordered_default_mission_date']) ?>
</p>

<p>
	<kbd><?php echo $label['mission_ext_ordered_default_stardate'] ?></kbd>
	<?php echo form_input($inputs['mission_ext_ordered_default_stardate']) ?>
</p>



