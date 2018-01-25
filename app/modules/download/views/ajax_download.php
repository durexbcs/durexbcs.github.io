<?php if(!empty($result)){
?>
<div class="row text-center">
    <div class="mediaInfo">
        <div class="item">
            <div class="thumbnail">
                <?php if($result->media_type == 1){?>
                <img src="<?=$result->image_versions2->candidates[0]->url?>">
                <?php }else{?>
                    <video controls autoplay muted>
                      <source src="<?=$result->video_versions[0]->url?>" type="video/mp4">
                      Your browser does not support the video tag.
                    </video>
                <?php }?>
                <div class="caption">
                    <h3 class="uc text-left mt0"><a class="col-light-green" href="https://www.instagram.com/<?=$result->user->username?>" target="_blank"><?=$result->user->username?></a></h3>
                    <p>
                        <?=$result->caption->text?>
                    </p>
                    <p>
                        <?php if($result->media_type == 1){?>
                        <a target="_blank" href="<?=$result->image_versions2->candidates[0]->url?>" class="btn btn-primary waves-effect" role="button"><?=l('Download')?></a>
                        <?php }else{?>
                            <a target="_blank" href="<?=$result->video_versions[0]->url?>" class="btn btn-primary waves-effect" role="button"><?=l('Download')?></a>
                        <?php }?>
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>