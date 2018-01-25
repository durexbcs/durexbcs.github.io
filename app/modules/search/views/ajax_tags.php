<div class="vttags">
	<?php if(!empty($result)){
	foreach ($result as $row) {
	?>
    <div class="item" data-tag="<?=$row->name?>">
        <input type="checkbox" id="md_checkbox_<?=$row->name?>" class="filled-in chk-col-blue" />
        <label for="md_checkbox_<?=$row->name?>">&nbsp;</label>
        <?=$row->name?> (<?=format_number($row->media_count)?>)
        <div class="icon-tag"></div>
    </div>
    <?php }}?>
</div>		