<?php echo text_output($title, 'h1', 'page-head');?>
<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>

<?php foreach($jsons['nova_ext_ordered_mission_posts'] as $key=>$field){ ?>
			<p>
				<kbd><?=$key?></kbd>
				<input type="text" name="<?=$key?>" value="<?=$field?>">	
			</p>
<?php } ?>
			<br>
			<button name="submit" type="submit" class="button-main" value="Submit"><span>Submit</span></button>
<?php echo form_close(); ?>
