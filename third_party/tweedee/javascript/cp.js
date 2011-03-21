/**
 * Tweedee CP behaviours.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee
 */

(function($) {

	/**
	 * Updates the available search criteria actions. If only one search criteria remains,
	 * the 'remove criterion' link should not be available.
	 *
	 * @return	void
	 */
	function updateSearchCriteriaActions() {
		$criteria = $('.tweedee_criterion');

		$criteria.removeClass('only_criterion');

		if ($criteria.size() == 1) {
			$criteria.first().addClass('only_criterion');
		}
	}

	/**
	 * Re-indexes the 'search settings' criteria, after a criterion is added or deleted.
	 *
	 * @return	void
	 */
	function updateSearchCriteriaIndex() {
		regex = /^([a-z_]+)\[(?:[0-9]+)\](.*)$/;

		$('.tweedee_criterion').each(function(criterionCount) {
			$select = $(this).find('select');
			$select.attr('name' , $select.attr('name').replace(regex, '$1[' + criterionCount + ']$2'));
		});
	}


	/**
	 * Enhances the 'search settings' form.
	 *
	 * @return	void
	 */
	function iniSearchSettings() {

		updateSearchCriteriaActions();

		// Add criterion.
		$('.add_criterion').live('click', function() {
			$criterion	= $(this).closest('.tweedee_criterion');
			$clone		= $criterion.clone();

			// Reset the clone.
			$clone.find('select').val('');
			$clone.find('input').val('');

			// Add the new criterion.
			$criterion.after($clone);

			// Housekeeping.
			updateSearchCriteriaIndex();
			updateSearchCriteriaActions();

			return false;
		});

		// Remove criterion.
		$('.remove_criterion').live('click', function() {
			$criterion	= $(this).closest('.tweedee_criterion');

			// Can't remove the only criterion.
			if ($criterion.siblings('.tweedee_criterion').length > 0) {
				$criterion.remove();
			}

			// Housekeeping.
			updateSearchCriteriaIndex();
			updateSearchCriteriaActions();
			
			return false;
		});
	}


	/* ---------------------------------------------
	 * Get the ball rolling...
	 * ------------------------------------------ */

	$(document).ready(function() {
		iniSearchSettings();
	});

})(jQuery);

/* End of file		cp.js */
/* File location	third_party/tweedee/javascript/cp.js */
