<div id="tweedee">

<?=form_open($form_action); ?>
	<p>Find Tweets matching the following criteria:</p>
	
	<?php
		if ($search_criteria):
		$criterion_count = 0;
		foreach ($search_criteria AS $search_criterion):
	?>
	<div class="tweedee_criterion">
		<label>
			<span><?=lang('lbl_search_criterion_type'); ?></span>
			<?=form_dropdown("search_criteria[{$criterion_count}][type]", $criterion_types, $search_criterion['criterion_type']); ?>
		</label>
	
		<label>
			<span><?=lang('lbl_search_criterion_value'); ?></span>
			<?=form_input("search_criteria[{$criterion_count}][value]", $search_criterion['criterion_value']); ?>
		</label>

		<div>
			<a class="remove_criterion" href="#" title="<?=lang('lbl_remove_criterion'); ?>"><img src="<?=$theme_url; ?>/img/minus.png" /></a>
			<a class="add_criterion" href="#" title="<?=lang('lbl_add_criterion'); ?>"><img src="<?=$theme_url; ?>/img/plus.png" /></a>
		</div>
	</div><!-- /.tweedee_criterion -->
	<?php
		$criterion_count++;
		endforeach;
		else:
	?>
	<div class="tweedee_criterion">
		<label>
			<span><?=lang('lbl_search_criterion_type'); ?></span>
			<?=form_dropdown('search_criteria[0][type]', $criterion_types); ?>
		</label>
	
		<label>
			<span><?=lang('lbl_search_criterion_value'); ?></span>
			<?=form_input('search_criteria[0][value]', ''); ?>
		</label>

		<div>
			<a class="remove_criterion" href="#" title="<?=lang('lbl_remove_criterion'); ?>"><img src="<?=$theme_url; ?>/img/minus.png" /></a>
			<a class="add_criterion" href="#" title="<?=lang('lbl_add_criterion'); ?>"><img src="<?=$theme_url; ?>/img/plus.png" /></a>
		</div>
	</div><!-- /.tweedee_criterion -->
	<?php endif; ?>

<?php
	
	echo '<div class="submit_wrapper">';
	echo form_submit(array('name' => 'submit', 'value' => lang('lbl_save_search_criteria'), 'class' => 'submit'));
	echo '</div>';
	echo form_close();

?>

</div><!-- /#tweedee -->
