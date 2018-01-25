<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <i class="fa fa-user" aria-hidden="true"></i> <?=l('Update profile')?> 
                </h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-sm-12 mb0">
                        <?php if(!empty($package)){
                            $permission = json_decode($package->permission);
                        ?>
                        <ul class="list-group">
                            <li class="list-group-item active"><?=l('Package info')?></li>
                            <li class="list-group-item"><?=$package->name?>
                                <?php if($package->type != 0){?>
                                    <span class="badge bg-black"><?=!empty($result)?date('d-m-Y',strtotime($result->expiration_date)):""?></span>
                                <?php }?>
                            </li>
                            <li class="list-group-item"><?=l('Maximum instagram accounts')?><span class="badge bg-black"><?=$permission->maximum_account?></span></li>
                        </ul>
                        <?php }?>
                        <form action="<?=cn('ajax_profile')?>" data-redirect="<?=current_url()?>">
                            <input type="hidden" class="form-control" name="id" value="<?=!empty($result)?$result->id:""?>">
                            <b><?=l('Fullname')?></b>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="fullname" value="<?=!empty($result)?$result->fullname:""?>">
                                </div>
                            </div>
                            <b><?=l('Email')?></b>
                            <div class="form-group">
                                <div class="form-line bg-grey">
                                    <input type="text" class="form-control" name="email" value="<?=!empty($result)?$result->email:""?>" disabled="" >
                                </div>
                            </div>
                            <b><?=l('Time zone')?></b>
                            <div class="form-group">
                                <select name="timezone" class="form-control">
                                <?php foreach(tz_list() as $t) { ?>
                                    <option value="<?=$t['zone'] ?>" <?=(!empty($result) && $result->timezone == $t['zone'])?"selected":""?>>
                                        <?=$t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                                    </option>
                                <?php } ?>
                                </select>
                            </div>
                            <b><?=l('Password')?></b>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <b><?=l('Re-password')?></b>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" class="form-control" name="repassword">
                                </div>
                            </div>
                            <button type="submit" class="btn bg-red waves-effect btnActionUpdate"><?=l('Submit')?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>