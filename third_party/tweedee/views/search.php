<div id="tweedee">

<form>
	<p>Find Tweets matching the following criteria:</p>
	
	<div class="tweedee_criterion">
		<label>
			<span>Search criterion type</span>
			<select name="search_criteria[0][type]">
				<option value="">Please select&hellip;</option>
				<option value="from">From this person</option>
				<option value="to">To this person</option>
				<option value="referencing">Referencing this person</option>
				<option value="hashtag">Using this hashtag</option>
				<option value="ors">Containing any of these words</option>
				<option value="ands">Containing all of these words</option>
				<option value="phrase">Containing this exact phrase</option>
				<option value="nots">Not containing these words</option>
			</select>
		</label>
	
		<label>
			<span>Search criterion value</span>
			<input name="search_criteria[0][value]" type="text" />
		</label>

		<div>
			<a class="remove_criterion" href="#" title="Remove this search criterion"><img src="<?=$theme_url; ?>/img/minus.png" /></a>
			<a class="add_criterion" href="#" title="Add a new search criterion"><img src="<?=$theme_url; ?>/img/plus.png" /></a>
		</div>
	</div><!-- /.tweedee_criterion -->

<?php
	
	echo '<div class="submit_wrapper">';
	echo form_submit(array('name' => 'submit', 'value' => lang('lbl_save_settings'), 'class' => 'submit'));
	echo '</div>';
	echo form_close();

?>

</div><!-- /#tweedee -->
