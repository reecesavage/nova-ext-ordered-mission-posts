<p>
	<kbd><?php echo $label['nova_ext_ordered_config_setting'] ?></kbd>
	<?php echo form_dropdown($inputs['nova_ext_ordered_config_setting'],$option['nova_ext_ordered_config_setting'],$value['nova_ext_ordered_config_setting'],$configId['nova_ext_ordered_config_setting']) ?>
</p>
<p class="nova_ext_ordered_label_post_day">
	<kbd><?php echo $label['nova_ext_ordered_post_day'] ?></kbd>
	<?php echo form_input($inputs['nova_ext_ordered_post_day']) ?>
</p>
<p class="nova_ext_ordered_label_post_date">
	<kbd><?php echo $label['nova_ext_ordered_post_date'] ?></kbd>
	<?php echo form_input($inputs['nova_ext_ordered_post_date']) ?>
</p>

<p class="nova_ext_ordered_label_post_stardate">
	<kbd><?php echo $label['nova_ext_ordered_post_stardate'] ?></kbd>
	<?php echo form_input($inputs['nova_ext_ordered_post_stardate']) ?>
</p>

<p class="nova_ext_ordered_label_post_time">
	<kbd><?php echo $label['nova_ext_ordered_post_time'] ?></kbd>
	<?php echo form_input($inputs['nova_ext_ordered_post_time']) ?>
</p>