<div class="vttags">
	<?php if(!empty($result)){
	foreach ($result as $row) {
	?>
    <div class="item" data-id="<?=$row->pk?>" data-username="<?=$row->username?>">
        <input type="checkbox" id="md_checkbox_<?=$row->username?>" class="filled-in chk-col-blue" />
        <label for="md_checkbox_<?=$row->username?>">&nbsp;</label>
        <img src="<?=$row->profile_pic_url?>">
        <a href="https://www.instagram.com/<?=$row->username?>" target="_blank"><?=$row->username?> (<?=$row->follower_count?>)<a>
        <div class="icon-tag"></div>
    </div>
    <?php }}?>
</div>		