<?php echo text_output($title, 'h1', 'page-head');?>

<?php if(!empty($fields)){ ?>
<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/checkExtension/');?>
        

			<p>
				<kbd>Select Fields</kbd>
				<select name="attribute">
				<?php foreach($fields as $key=>$field){?>
                  <option value="<?=$field?>"><?=$field?></option>
				<?php }?>
				</select>
			</p>

			<br>
			<button name="submit" type="submit" class="button-main" value="Submit"><span>Submit</span></button>
<?php echo form_close(); ?>
<?php } else { ?>
   <div>All the fields are available in database</div>
<?php } ?>


<?php if(empty($write)){ ?>

	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/checkExtension/');?>
     <br>
	<button name="submit" type="submit" class="button-main" value="write"><span>Add Email Function</span></button>


	<?php echo form_close(); ?>
<?php } else { ?>
   <div class="email-message"><br>Email Function is Exists</div>
<?php } ?>



