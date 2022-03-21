<?php echo text_output($title, 'h1', 'page-head');?>
<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>

<?php foreach($jsons['nova_ext_ordered_mission_posts'] as $key=>$field){ ?>
			<p>
				<kbd><?=$field['name']?></kbd>
				<input type="text" name="<?=$key?>" value="<?=$field['value']?>">	
			</p>
<?php } ?>
			<br>
			<button name="submit" type="submit" class="button-main" value="Submit"><span>Update Labels</span></button>
<?php echo form_close(); ?>



<?php if(!empty($fields)){ ?>
<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
        

			<p>
				<kbd>Database Columns Missing - This is expected if it is the first time you have used this Extension or an update has produced a change. Click the Create Column button below for each missing column or check the README file for manual instructions.</kbd>
				<select name="attribute">
				<?php foreach($fields as $key=>$field){?>
                  <option value="<?=$field?>"><?=$field?></option>
				<?php }?>
				</select>
			</p>

			<br>
			<button name="submit" type="submit" class="button-main" value="Add"><span>Create Column</span></button>
<?php echo form_close(); ?>
<?php } else { ?>
   <div><br>All expected columns found in the database</div>
    

    <?php  if(empty($postFlag) ||empty($missionFlag)){?>

    
   <?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
        

        <br>
			<button name="submit" type="submit" class="button-main" value="createIndex"><span>Create Index</span></button>

   	<?php echo form_close(); ?>

   <?php } else {?>
     <div><br>All expected indexes found in the database.</div>
   <?php }?>
<?php } ?>


<?php if(empty($write)){ ?>

	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
	<br>
	<div>Email Configuration Missing or Updated - This is expected if it is the first time you have used this Extension or an update has produced a change. Click the button below to modify your application/controlers/write.php file or check the README file for manual instructions.</div>
	<br>
     
	<button name="submit" type="submit" class="button-main" value="write"><span>Update Controller Configuration</span></button>


	<?php echo form_close(); ?>
<?php } else { ?>
   <div class="email-message"><br>Email Configuration located, and up to date.</div>
<?php } ?>




<?php if(empty($feed)){ ?>

	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
	<br>
	<div>Rss Feed Configuration Missing or Updated - This is expected if it is the first time you have used this Extension or an update has produced a change. Click the button below to modify your application/controlers/feed.php file or check the README file for manual instructions.</div>
	<br>
     
	<button name="submit" type="submit" class="button-main" value="feed"><span>Update Feed Controller Configuration</span></button>


	<?php echo form_close(); ?>
<?php } else { ?>
   <div class="email-message"><br>Rss Feed located, and up to date.</div>
<?php } ?>



	

	<?php if(!empty($checkLegacy)){ ?>

     <div class="email-message"><br>chronological_mission_posts database columns not detected. You may not enable Legacy Mode for existing Missions</div>

      <?php } ?>
	<?php if(!empty($checkPostChronological)){ ?>


		<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
	<br>
          <div>chronological_mission_posts database columns detected. You may enable Legacy Mode for existing Missions.</div>
	<p>
				<kbd>Legacy Mode</kbd>
				<input type="checkbox" name="legacy_mode" value="1" <?=(isset($jsons['setting']['legacy_mode'])&&$jsons['setting']['legacy_mode']==1 )?'checked':''?>>	
			</p>

	
     
	<button name="submit" type="submit" class="button-main" value="legacySubmit"><span>Enable/Disable Legacy Mode</span></button>


	<?php echo form_close(); ?>

	<?php } ?>


