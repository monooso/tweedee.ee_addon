<?=form_open($form_action); ?>
<div id="tweedee">

<table class="mainTable padTable" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th width="40%"><?=lang('thd_search_criterion_type'); ?></th>
            <th><?=lang('thd_search_criterion_value'); ?></th>
            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody class="roland">
	<?php
        if ( ! $search_criteria):
    ?>
        <tr class="row">
            <td><?=form_dropdown('search_criteria[0][type]', $criterion_types); ?></td>
            <td><?=form_input('search_criteria[0][value]', ''); ?></td>
            <td class="act">
                <a class="remove_row btn" href="#" title="<?=lang('lbl_remove_criterion'); ?>"><img src="<?=$theme_url; ?>/img/minus.png" /></a>
                <a class="add_row btn" href="#" title="<?=lang('lbl_add_criterion'); ?>"><img src="<?=$theme_url; ?>/img/plus.png" /></a>
            </td>
        </tr>
    <?php
        else:
        foreach ($search_criteria AS $criterion):
	?>
        <tr class="row">
            <td><?=form_dropdown('search_criteria[0][type]', $criterion_types, $criterion->get_criterion_type()); ?></td>
            <td><?=form_input('search_criteria[0][value]', $criterion->get_criterion_value()); ?></td>
            <td class="act">
                <a class="remove_row btn" href="#" title="<?=lang('lbl_remove_criterion'); ?>"><img src="<?=$theme_url; ?>/img/minus.png" /></a>
                <a class="add_row btn" href="#" title="<?=lang('lbl_add_criterion'); ?>"><img src="<?=$theme_url; ?>/img/plus.png" /></a>
            </td>
        </tr>
    <?php
        endforeach;
        endif;
    ?>
    </tbody>
</table>

</div><!-- /#tweedee -->

<div class="submit_wrapper"><?=form_submit(array('name' => 'submit', 'value' => lang('lbl_save_search_criteria'), 'class' => 'submit')); ?></div>
<?=form_close(); ?>
