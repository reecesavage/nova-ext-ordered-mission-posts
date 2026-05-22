<?php
	$stateLabels = array(
		'current'      => 'Installed and up to date',
		'outdated'     => 'Installed but outdated - update available',
		'legacy'       => 'Older unmarked version present - manual update required',
		'missing'      => 'Not installed',
		'missing_file' => 'Controller file not found',
	);

	$missingColumnList = array_merge($missing_columns['posts'], $missing_columns['missions']);
?>

<?php echo text_output($title, 'h1', 'page-head');?>


<?php /* ---------- Status ---------- */ ?>

<?php echo text_output('Status', 'h3', 'page-subhead');?>

<table class="table100 zebra">
	<tbody>
		<tr>
			<td class="cell-label">Database columns</td>
			<td class="cell-spacer"></td>
			<td>
				<?php if (empty($missingColumnList)): ?>
					All present
				<?php else: ?>
					<?php echo count($missingColumnList);?> missing
					(<?php echo implode(', ', $missingColumnList);?>)
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td class="cell-label">Database indexes</td>
			<td class="cell-spacer"></td>
			<td>
				<?php if (empty($missing_indexes)): ?>
					All present
				<?php else: ?>
					<?php echo count($missing_indexes);?> missing
					(<?php echo implode(', ', $missing_indexes);?>)
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td class="cell-label">Post email code</td>
			<td class="cell-spacer"></td>
			<td><?php echo $stateLabels[$email_state];?></td>
		</tr>
		<tr>
			<td class="cell-label">RSS feed code</td>
			<td class="cell-spacer"></td>
			<td><?php echo $stateLabels[$feed_state];?></td>
		</tr>
		<tr>
			<td class="cell-label">Legacy mode</td>
			<td class="cell-spacer"></td>
			<td>
				<?php if ($legacy_available): ?>
					Available (chronological_mission_posts columns detected)
				<?php else: ?>
					Not available - chronological_mission_posts columns not present
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>

<br>


<?php /* ---------- Database setup ---------- */ ?>

<?php echo text_output('Database', 'h3', 'page-subhead');?>

<?php if ( ! $db_ready): ?>
	<p>
		One click will add every missing column and create any missing indexes
		on <code><?php echo $this->db->dbprefix;?>posts</code> and
		<code><?php echo $this->db->dbprefix;?>missions</code>. Safe to re-run.
	</p>
	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
		<button name="action" type="submit" class="button-main" value="setup_database"><span>Set Up Database</span></button>
	<?php echo form_close();?>
<?php else: ?>
	<p>All required columns and indexes are present.</p>
<?php endif; ?>

<br>


<?php /* ---------- Post email code ---------- */ ?>

<?php echo text_output('Post email code', 'h3', 'page-subhead');?>

<?php if ($email_state === 'current'): ?>
	<p>The post email code in <code>application/controllers/Write.php</code> is up to date.</p>

<?php elseif ($email_state === 'legacy'): ?>
	<p>
		An older version of <code>_email()</code> is present in
		<code>application/controllers/Write.php</code> without the managed-block markers, so it can't be
		updated automatically. Remove the existing <code>_email()</code> method from that file (or replace
		<code>Write.php</code> with the stock Nova stub), then come back and click Install.
	</p>

<?php elseif ($email_state === 'missing_file'): ?>
	<p>
		<code>application/controllers/Write.php</code> was not found. Restore the file from your Nova install before continuing.
	</p>

<?php else: ?>
	<p>
		<?php if ($email_state === 'outdated'): ?>
			The injected code in <code>application/controllers/Write.php</code> is out of date and will be replaced.
		<?php else: ?>
			Inject the post-numbering shim into <code>application/controllers/Write.php</code> so mission post emails
			can include the post number.
		<?php endif; ?>
	</p>
	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
		<button name="action" type="submit" class="button-main" value="install_email">
			<span><?php echo ($email_state === 'outdated') ? 'Update Email Code' : 'Install Email Code';?></span>
		</button>
	<?php echo form_close();?>
<?php endif; ?>

<br>


<?php /* ---------- Feed code ---------- */ ?>

<?php echo text_output('RSS feed code', 'h3', 'page-subhead');?>

<?php if ($feed_state === 'current'): ?>
	<p>The RSS feed code in <code>application/controllers/Feed.php</code> is up to date.</p>

<?php elseif ($feed_state === 'legacy'): ?>
	<p>
		An older version of <code>posts()</code> is present in
		<code>application/controllers/Feed.php</code> without the managed-block markers, so it can't be
		updated automatically. Remove the existing <code>posts()</code> method from that file (or replace
		<code>Feed.php</code> with the stock Nova stub), then come back and click Install.
	</p>

<?php elseif ($feed_state === 'missing_file'): ?>
	<p>
		<code>application/controllers/Feed.php</code> was not found. Restore the file from your Nova install before continuing.
	</p>

<?php else: ?>
	<p>
		<?php if ($feed_state === 'outdated'): ?>
			The injected feed code in <code>application/controllers/Feed.php</code> is out of date and will be replaced.
		<?php else: ?>
			Inject the enhanced RSS feed shim into <code>application/controllers/Feed.php</code> so
			<code>/feed/posts</code> includes timeline information for ordered missions.
		<?php endif; ?>
	</p>
	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
		<button name="action" type="submit" class="button-main" value="install_feed">
			<span><?php echo ($feed_state === 'outdated') ? 'Update Feed Code' : 'Install Feed Code';?></span>
		</button>
	<?php echo form_close();?>
<?php endif; ?>

<br>


<?php /* ---------- Labels ---------- */ ?>

<?php echo text_output('Labels', 'h3', 'page-subhead');?>

<p>Customise the wording shown on the mission form, post form, and post views.</p>

<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
	<?php foreach ($jsons['nova_ext_ordered_mission_posts'] as $key => $field): ?>
		<p>
			<kbd><?php echo $field['name'];?></kbd>
			<input type="text" name="<?php echo $key;?>" value="<?php echo htmlspecialchars($field['value'], ENT_QUOTES);?>">
		</p>
	<?php endforeach; ?>
	<br>
	<button name="action" type="submit" class="button-main" value="save_labels"><span>Update Labels</span></button>
<?php echo form_close();?>

<br>


<?php /* ---------- Legacy mode ---------- */ ?>

<?php if ($legacy_available): ?>
	<?php echo text_output('Legacy mode (chronological_mission_posts)', 'h3', 'page-subhead');?>
	<p>
		The columns from the old <code>chronological_mission_posts</code> extension were detected. Enabling legacy mode
		lets existing missions reuse those Day/Time values when their timeline configuration is set to Day Time.
	</p>
	<?php echo form_open('extensions/nova_ext_ordered_mission_posts/Manage/config/');?>
		<p>
			<kbd>Legacy mode enabled</kbd>
			<input type="checkbox" name="legacy_mode" value="1" <?php echo $legacy_enabled ? 'checked' : '';?>>
		</p>
		<button name="action" type="submit" class="button-main" value="save_legacy"><span>Save Legacy Mode</span></button>
	<?php echo form_close();?>
<?php endif; ?>
