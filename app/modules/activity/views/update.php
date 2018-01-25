<?php 
$schedule_default = SCHEDULE_DEFAULT;
$schedule_default = json_decode($schedule_default);
$targets_default = json_decode($schedule_default->target);
$speed_default = $schedule_default->speed;
$target_hashtag = $targets_default->tag;
$target_timeline = $targets_default->timeline;
$target_popular = $targets_default->popular;
$target_your_feed = $targets_default->your_feed;
$target_explore_tab = $targets_default->explore_tab;
$target_location = $targets_default->location;
$target_username = $targets_default->username;
$todo = json_decode($schedule_default->todo);
$tags = json_decode($schedule_default->tags);
$locations = json_decode($schedule_default->locations);
$comments = json_decode($schedule_default->comments);
$messages = json_decode($schedule_default->messages);
$filter = json_decode($schedule_default->filter);
$usernames = array();
switch ($speed_default) {
    case 1:
        $slow = json_decode($schedule_default->slow);
        $delay = (int)$slow->delay;
        $speed_like = (int)$slow->like;
        $speed_comment = (int)$slow->comment;
        $speed_deletemedia = (int)$slow->deletemedia;
        $speed_follow = (int)$slow->follow;
        $speed_followback = (int)$slow->followback;
        $speed_unfollow = (int)$slow->unfollow;
        $speed_repost = (int)$slow->repost;
        break;
    case 2:
        $medium = json_decode($schedule_default->medium);
        $delay = (int)$medium->delay;
        $speed_like = (int)$medium->like;
        $speed_comment = (int)$medium->comment;
        $speed_deletemedia = (int)$slow->deletemedia;
        $speed_follow = (int)$medium->follow;
        $speed_followback = (int)$medium->followback;
        $speed_unfollow = (int)$medium->unfollow;
        $speed_repost = (int)$medium->repost;
        break;
    case 3:
        $fast = json_decode($schedule_default->fast);
        $delay = (int)$fast->delay;
        $speed_like = (int)$fast->like;
        $speed_comment = (int)$fast->comment;
        $speed_deletemedia = (int)$slow->deletemedia;
        $speed_follow = (int)$fast->follow;
        $speed_followback = (int)$fast->followback;
        $speed_unfollow = (int)$fast->unfollow;
        $speed_repost = (int)$fast->repost;
        break;
}

if(!empty($item)){
    $target_hashtag = false;
    $target_timeline = false;
    $target_popular = false;
    $target_your_feed = false;
    $target_explore_tab = false;
    $target_location = false;
    $target_username = false;

    $schedule = json_decode($item->data);
    $todo = json_decode($schedule->todo);
    $targets = json_decode($schedule->targets);
    $comments = json_decode($schedule->comments);
    $locations = json_decode($schedule->locations);
    $usernames = json_decode($schedule->usernames);
    $messages = json_decode($schedule->messages);
    $speed = json_decode($schedule->speed);
    $tags = json_decode($schedule->tags);
    $filter = json_decode($schedule->filter);

    if($targets->tag == 1){ $target_hashtag = true; }
    if($targets->timeline == 1){ $target_timeline = true; }
    if($targets->popular == 1){ $target_popular = true; }
    if($targets->your_feed == 1){ $target_your_feed = true; }
    if($targets->explore_tab == 1){ $target_explore_tab = true; }
    if($targets->location == 1){ $target_location = true; }
    if($targets->username == 1){ $target_username = true; }
    $speed_like = (int)$speed->like;
    $speed_comment = (int)$speed->comment;
    $speed_deletemedia = (int)$speed->deletemedia;
    $speed_follow = (int)$speed->follow;
    $speed_followback = (int)$speed->followback;
    $speed_unfollow = (int)$speed->unfollow;
    $speed_repost = (int)$speed->repost;
    $delay = (int)$speed->delay;
    $speed_default = (int)$speed->type;
}
?>

<script type="text/javascript">
    var activity_speed = [
        <?=$schedule_default->slow?>,
        <?=$schedule_default->medium?>,
        <?=$schedule_default->fast?>
    ];
</script>

<div class="row">
    <form class="formSchedule" action="javascript:void(0);" data-type="all" data-action="<?=url("schedules/ajax_add_multi_schedules")?>" data-redirect="<?=cn()?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header uc">
                <h2>
                    <i class="fa fa-heartbeat col-pink" aria-hidden="true"></i> <?=l('Auto Activity')?>
                </h2>
            </div>
            <div class="body pb0">
                <div class="row">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12 mb0">
                        <div class="panel-group full-body" id="accordion_22" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_22">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseThree_22" aria-expanded="false" aria-controls="collapseThree_22">
                                            <i class="fa fa-users col-black" aria-hidden="true"></i> <?=l('Accounts')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_22" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree_22" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="list-instagram-accounts">
                                        <?php if(!empty($accounts)){
                                        foreach ($accounts as $key => $row) {
                                        ?>
                                        <div class="item">
                                            <img src="<?=$row->avatar?>">
                                            <input type="checkbox" name="accounts[]" value="<?=$row->id?>" id="md_checkbox_<?=$row->fid?>" class="filled-in chk-col-blue" <?=(!empty($item))?"checked":""?> />
                                            <label for="md_checkbox_<?=$row->fid?>">&nbsp;</label>
                                            <div class="username"><?=$row->username?></div>
                                        </div>
                                        <?php }}?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingOne_todo">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseOne_todo" aria-expanded="true" aria-controls="collapseOne_todo">
                                            <i class="fa fa-tasks col-light-green" aria-hidden="true"></i> <?=l('To do')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne_todo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_todo" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="row mb0">
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Like')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_like" <?=($todo->like==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Comment')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_comment" <?=($todo->comment==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Follow')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_follow" <?=($todo->follow==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Unfollow')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_unfollow" <?=($todo->unfollow==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Follow back')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_followback" <?=($todo->followback==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Repost media')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_repost" <?=($todo->repost==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Delete media')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="todo_deletemedia" <?=($todo->deletemedia==1)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingOne_19">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseOne_19" aria-expanded="true" aria-controls="collapseOne_19">
                                            <i class="fa fa-dot-circle-o col-red" aria-hidden="true"></i> <?=l('Targeting')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne_19" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_19" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="row mb0">
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Hashtags')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_tag" <?=($target_hashtag)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Locations')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_location" <?=($target_location)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Usernames')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_username" <?=($target_username)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Timeline Feed')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_timeline" <?=($target_timeline)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Popular feed')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_popular" <?=($target_popular)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Your Feed')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_your_feed" <?=($target_your_feed)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Explore Tab Feed')?>
                                                        <span class="badge bg-none">
                                                            <div class="switch">
                                                                <label><input type="checkbox" name="enable_explore_tab" <?=($target_explore_tab)?"checked":""?>><span class="lever switch-col-light-green"></span></label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_20">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseThree_20" aria-expanded="false" aria-controls="collapseThree_20">
                                            <i class="fa fa-tachometer col-blue" aria-hidden="true"></i> <?=l('Speed')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_20" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_20" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="row mb0">
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Activity speed')?>
                                                        <span class="badge bg-none">
                                                            <select name="speed" class="form-control show-tick activity_speed">
                                                                <option value="1" <?=($speed_default == 1)?"selected":""?>><?=l('Slow')?></option>
                                                                <option value="2" <?=($speed_default == 2)?"selected":""?>><?=l('Medium')?></option>
                                                                <option value="3" <?=($speed_default == 3)?"selected":""?>><?=l('Fast')?></option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Likes / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_like" class="form-control show-tick repeat_like">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_like == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Comments / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_comment" class="form-control show-tick repeat_comment">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_comment == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Follows / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_follow" class="form-control show-tick repeat_follow">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_follow == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Delete media / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_deletemedia" class="form-control show-tick repeat_deletemedia">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_deletemedia == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Follows back / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_followback" class="form-control show-tick repeat_followback">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_unfollow == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Unfollows / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_unfollow" class="form-control show-tick repeat_unfollow">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_followback == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Reposts / hour (post)')?>
                                                        <span class="badge bg-none">
                                                            <select name="repeat_repost" class="form-control show-tick repeat_repost">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($speed_repost == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Delay on each account (minutes)')?>
                                                        <span class="badge bg-none">
                                                            <select name="delay" class="form-control show-tick repeat_delay">
                                                                <?php for($i = 1; $i <= 60; $i++){?>
                                                                    <option value="<?=$i?>" <?=($delay == $i)?"selected":""?>><?=$i?></option>
                                                                <?php }?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_filter">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseThree_filter" aria-expanded="false" aria-controls="collapseThree_filter">
                                            <i class="fa fa-filter" aria-hidden="true"></i> <?=l('Filters')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_filter" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_filter" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="row mb0">
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Media age')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Media age')?>" data-content="<?=l("This setting will help you to choose an age of media you want to interact with. From the newest one to the oldest.<br/><br/> For example: select <b>1 Day</b> if you want to interact only with media that not older than one day.<br/><br/><b>Newest</b> media age was previously known as <b>New media only</b>.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <select name="filter_media_age" class="form-control">
                                                                <option value="new" <?=($filter->media_age == "new")?"selected":""?>><?=l('Newest')?></option>
                                                                <option value="1h" <?=($filter->media_age == "1h")?"selected":""?>><?=l('1 Hour')?></option>
                                                                <option value="12h" <?=($filter->media_age == "12h")?"selected":""?>><?=l('12 Hours')?></option>
                                                                <option value="1d" <?=($filter->media_age == "1d")?"selected":""?>><?=l('1 Day')?></option>
                                                                <option value="3d" <?=($filter->media_age == "3d")?"selected":""?>><?=l('3 Danys')?></option>
                                                                <option value="1w" <?=($filter->media_age == "1w")?"selected":""?>><?=l('1 Week')?></option>
                                                                <option value="2w" <?=($filter->media_age == "2w")?"selected":""?>><?=l('2 Weeks')?></option>
                                                                <option value="1M" <?=($filter->media_age == "1M")?"selected":""?>><?=l('1 Month')?></option>
                                                                <option value="" <?=($filter->media_age == "")?"selected":""?>><?=l('Any')?></option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Media type')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Media type')?>" data-content="<?=l("This setting lets you interact only with specific media type: <b>Photos</b> or <b>Videos</b>. Also, you can choose <b>Any</b> to interact with any media type.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <select name="filter_media_type" class="form-control">
                                                                <option value="" <?=($filter->media_type == "")?"selected":""?>><?=l('Any')?></option>
                                                                <option value="image" <?=($filter->media_type == "image")?"selected":""?>><?=l('Photos')?></option>
                                                                <option value="video" <?=($filter->media_type == "video")?"selected":""?>><?=l('Videos')?></option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Min. likes filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Min. likes filter')?>" data-content="<?=l("Interact only with media that have minimum selected amount of likes.<br/><br/> Use it along with <b>Max. likes filter</b> to set desired range of media popularity.<br/><br/> Recommended value: 0.<br/><br/> Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_min_likes" value="<?=($filter->min_likes != "")?$filter->min_likes:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Max. likes filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Max. likes filter')?>" data-content="<?=l("Interact only with media that have maximum selected amount of likes.<br/><br/>Use it along with <b>Min. likes filter</b> to set desired rangeof media popularity.<br/><br/>Recommended values: 50-100.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_max_likes" value="<?=($filter->max_likes != "")?$filter->max_likes:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Min. comments filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Min. comments filter')?>" data-content="<?=l("Interact only with media that have minimum selected amount of comments.<br/><br/>Use it along with <b>Max. comments filter</b> to set desired rangeof media popularity.<br/><br/>Recommended value: 0.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_min_comments" value="<?=($filter->min_comments != "")?$filter->min_comments:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Max. comments filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Max. comments filter')?>" data-content="<?=l("Interact only with media that have maximum selected amount of comments.<br/><br/>Use it along with <b>Min. comments filter</b> to set desired rangeof media popularity.<br/><br/>Recommended values: 20-50.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_max_comments" value="<?=($filter->max_comments != "")?$filter->max_comments:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('User relation filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('User relation filter')?>" data-content="<?=l("This filter will help you to exclude your own followers/followings from Liking, Commenting and Following activity:<br/><br/><b>Off</b> - Filter is turned off.<br/><br/><b>Followers</b> - You will not interact with your followers and their media.<br/><br/><b>Followings</b> - You will not interact with your followings and their media.<br/><br/><b>Both</b> - You will not interact with your followers and followings and their media.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <select name="filter_user_relation" class="form-control">
                                                                <option value="" <?=($filter->user_relation == "")?"selected":""?>><?=l('Off')?></option>
                                                                <option value="followers" <?=($filter->user_relation == "followers")?"selected":""?>><?=l('Followers')?></option>
                                                                <option value="followings" <?=($filter->user_relation == "followings")?"selected":""?>><?=l('Followings')?></option>
                                                                <option value="both" <?=($filter->user_relation == "both")?"selected":""?>><?=l('Both')?></option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('User profile filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="Media age" data-content="<?=l("This filter will help you to avoid inappropriate and unwanted users and their media during your activity:<br/><br/><b>Off</b> - Filter is turned off.<br/><br/><b>Low</b> - Excludes users who have no avatar or have no posted media.<br/><br/><b>Medium</b> - Excludes users who have no avatar, have less than 10 postedmedia or have no name in the profile.<br/><br/><b>High</b> - Excludes users who have no avatar, have less than 30 postedmedia, have no name in the profile or have no bio.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <select name="filter_user_profile" class="form-control">
                                                                <option value="" <?=($filter->user_profile == "")?"selected":""?>><?=l('Off')?></option>
                                                                <option value="low" <?=($filter->user_profile == "low")?"selected":""?>><?=l('Low')?></option>
                                                                <option value="medium" <?=($filter->user_profile == "medium")?"selected":""?>><?=l('Medium')?></option>
                                                                <option value="height" <?=($filter->user_profile == "height")?"selected":""?>><?=l('High')?></option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Min. followers filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Min. followers filter')?>" data-content="<?=l("Interact only with users that have minimum selected amount of followers.<br/><br/>Use it along with <b>Max. followers filter</b> to set desired rangeof users popularity.<br/><br/>Recommended values: 0-50.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_min_followers" value="<?=($filter->min_followers != "")?$filter->min_followers:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Max. followers filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Max. followers filter')?>" data-content="<?=l("Interact only with users that have maximum selected amount of followers.<br/><br/>Use it along with <b>Min. followers filter</b> to set desired rangeof users popularity.<br/><br/>Recommended values: 500-1000.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_max_followers" value="<?=($filter->max_followers != "")?$filter->max_followers:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Min. followings filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Min. followings filter')?>" data-content="<?=l("Interact only with users that have minimum selected amount of followings.<br/><br/>Use it along with <b>Max. followings filter</b> to set desired rangeof users popularity.<br/><br/>Recommended values: 50-100.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_min_followings" value="<?=($filter->max_followings != "")?$filter->max_followings:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Max. followings filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="<?=l('Max. followings filter')?>" data-content="<?=l("Interact only with users that have maximum selected amount of followings.<br/><br/>Use it along with <b>Min. followings filter</b> to set desired rangeof users popularity.<br/><br/>Recommended values: 300-500.<br/><br/>Set to zero to disable this filter.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <input type="text" class="form-control" name="filter_max_followings" value="<?=($filter->max_followings != "")?$filter->max_followings:"0"?>">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 hidden">
                                                <div class="list-group mb0">
                                                    <div class="list-group-item">
                                                        <?=l('Gender filter')?>
                                                        <span class="help-tip" data-html="true" data-toggle="popover" data-placement="top" data-trigger="hover" title="" data-original-title="Media age" data-content="<?=l("<b>Off</b> - Filter is turned off.<br/><br/><b>Female</b> - Interact only with users and their mediawhose gender has been determined as female.<br/><br/><b>Male</b> - Interact only with users and their mediawhose gender has been determined as male.<br/><br/><span class='col-blue'>INFO:</span> This filter analyzes fullnames of the user profiles and cannot guarantee 100% accuracy.<br/><br/><span class='col-orange'>WARNING:</span> This filter can slow downor completely stop your activity if the system will not be ableto find accounts based on the selected option.")?>">?</span>
                                                        <span class="badge bg-none">
                                                            <select name="filter_gender" class="form-control pull-right">
                                                                <option value="" <?=($filter->gender == "")?"selected":""?>><?=l('Off')?></option>
                                                                <option value="f" <?=($filter->gender == "f")?"selected":""?>><?=l('Female')?></option>
                                                                <option value="m" <?=($filter->gender == "m")?"selected":""?>><?=l('Male')?></option>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_comment">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseThree_comment" aria-expanded="false" aria-controls="collapseThree_comment">
                                            <i class="fa fa-comments col-purple" aria-hidden="true"></i> <?=l('Comments')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_comment" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree_comment" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="vttags list-comments">
                                            <?php 
                                            if(!empty($comments)){
                                            foreach ($comments as $key => $row) {?>
                                                <div class="item" data-tag="<?=$row?>">
                                                    <?=$row?>
                                                    <input type="hidden" name="comments[]" value="<?=$row?>">
                                                    <div class="icon-remove btnRemoveTag">x</div>
                                                    <div class="icon-tag"></div>
                                                </div>
                                            <?php }}?>
                                            <div class="btn-group actionAddComments" role="group">
                                                <button type="button" class="btn bg-blue-grey waves-effect btnOpenAddComments"><?=l('Add')?></button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-blue-grey waves-effect dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block btnDeleteAllItem"><?=l('Delete all')?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_19">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseThree_19" aria-expanded="false" aria-controls="collapseThree_19">
                                            <i class="fa fa-hashtag col-blue-grey" aria-hidden="true"></i> <?=l('Hashtags')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_19" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree_19" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="vttags list-tags">
                                            <?php 
                                            if(!empty($tags)){
                                            foreach ($tags as $key => $row) {?>
                                                <div class="item" data-tag="<?=$row?>">
                                                    <?=$row?>
                                                    <input type="hidden" name="tags[]" value="<?=$row?>">
                                                    <div class="icon-remove btnRemoveTag">x</div>
                                                    <div class="icon-tag"></div>
                                                </div>
                                            <?php }}?>
                                            <div class="btn-group actionAddTags" role="group">
                                                <button type="button" class="btn bg-blue-grey waves-effect btnOpenAddTags"><?=l('Add')?></button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-blue-grey waves-effect dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block btnDeleteAllItem"><?=l('Delete all')?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_11">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseThree_11" aria-expanded="false" aria-controls="collapseThree_11">
                                            <i class="fa fa-map-marker col-red" aria-hidden="true"></i> <?=l('Locations')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_11" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree_11" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="vttags list-locations">
                                            <?php 
                                            if(!empty($locations)){
                                            foreach ($locations as $key => $row) {
                                                $array_row = explode("|", $row);
                                                if(count($array_row) == 4){
                                            ?>
                                                <div class="item" data-location="<?=$row?>">
                                                    <a href="https://www.instagram.com/explore/locations/<?=$array_row[3]?>" target="_blank"><?=$array_row[0]?></a>
                                                    <input type="hidden" name="locations[]" value="<?=$row?>">
                                                    <div class="icon-remove btnRemoveTag">x</div>
                                                    <div class="icon-tag"></div>
                                                </div>
                                            <?php }}}?>
                                            <div class="btn-group actionAddLocations" role="group">
                                                <button type="button" class="btn bg-blue-grey waves-effect btnOpenAddLocations"><?=l('Add')?></button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-blue-grey waves-effect dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block btnDeleteAllItem"><?=l('Delete all locations')?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_33">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseThree_33" aria-expanded="false" aria-controls="collapseThree_33">
                                            <i class="fa fa-user col-lime" aria-hidden="true"></i> <?=l('Usernames')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_33" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree_33" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="vttags list-usernames">
                                            <?php 
                                            if(!empty($usernames)){
                                            foreach ($usernames as $key => $row) {
                                                $array_row = explode("|", $row);
                                                if(count($array_row) == 2){
                                            ?>
                                                <div class="item" data-tag="<?=$array_row[1]?>">
                                                    <a href="https://www.instagram.com/<?=$array_row[1]?>" target="_blank"><?=$array_row[1]?></a>
                                                    <input type="hidden" name="usernames[]" value="<?=$row?>">
                                                    <div class="icon-remove btnRemoveTag">x</div>
                                                    <div class="icon-tag"></div>
                                                </div>
                                            <?php }}}?>
                                            <div class="btn-group actionAddUsernames" role="group">
                                                <button type="button" class="btn bg-blue-grey waves-effect btnOpenAddUsernames"><?=l('Add')?></button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-blue-grey waves-effect dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block btnDeleteAllItem"><?=l('Delete all usernames')?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-settings mb20">
                                <div class="panel-heading" role="tab" id="headingThree_message">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseThree_message" aria-expanded="false" aria-controls="collapseThree_message">
                                            <i class="fa fa-commenting col-deep-orange" aria-hidden="true"></i> <?=l('Message')?>
                                            <i class="fa fa-plus pull-right" aria-hidden="true"></i>
                                            <i class="fa fa-minus pull-right" aria-hidden="true"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree_message" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_message" aria-expanded="true">
                                    <div class="panel-body row mb0">
                                        <div class="vttags list-messages">
                                            <?php 
                                            if(!empty($messages)){
                                            foreach ($messages as $key => $row) {?>
                                                <div class="item" data-tag="<?=$row?>">
                                                    <?=$row?>
                                                    <input type="hidden" name="messages[]" value="<?=$row?>">
                                                    <div class="icon-remove btnRemoveTag">x</div>
                                                    <div class="icon-tag"></div>
                                                </div>
                                            <?php }}?>
                                            <div class="btn-group actionAddMessages" role="group">
                                                <button type="button" class="btn bg-blue-grey waves-effect btnOpenAddMessages"><?=l('Add')?></button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn bg-blue-grey waves-effect dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block btnDeleteAllItem"><?=l('Delete all')?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="btn-group right" role="group">
                    <button type="button" class="btn bg-light-green waves-effect btnAddSchedules"><i class="fa fa-play" aria-hidden="true"></i> <?=l('Submit')?></button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div> 
    </form>
</div>

<script type="text/javascript">
    /*$(function(){
        hash = window.location.hash;
        if(hash != undefined && hash == "#openvideo"){
            $( "#modal-how-to-use" ).modal('show').find(".modal-body").html('<iframe width="100%" height="315" src="https://player.vimeo.com/video/213740386?rel=0&autoplay=1&showinfo=0&controls=0" frameborder="0" allowfullscreen style="display: block;"></iframe>'); 
        }


        $(".btnCloseModelHowToUse,.close").click(function(){
            $('#modal-how-to-use').modal('toggle');
            $('iframe').remove();
            window.location.hash = "";
        });
        
    });*/
</script>