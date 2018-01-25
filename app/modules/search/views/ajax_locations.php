<div class="vttags">
	<?php if(!empty($result)){
	foreach ($result as $row) {
	?>
    <div class="item" data-name="<?=$row->name?>" data-location="<?=$row->name."|".$row->lat."|".$row->lng."|".$row->external_id?>">
        <input type="checkbox" id="md_checkbox_<?=$row->name?>" class="filled-in chk-col-blue" />
        <label for="md_checkbox_<?=$row->name?>">&nbsp;</label>
        <a href="https://www.instagram.com/explore/locations/<?=$row->external_id?>" target="_blank"><?=$row->name?></a>
        <div class="icon-tag"></div>
    </div>
    <?php }}?>
</div>		