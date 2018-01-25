<form  action="<?=url('user_management/ajax_timezone')?>" data-redirect="<?=url("add_account")?>">
    <div class="config-timezone">
        <div class="col-md-offset-4 col-sm-offset-3 col-md-4 col-sm-6 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <?=('Your timezone')?>
                        <small><?=l('Select your timezone to start your campaign')?></small>
                    </h2>
                </div>
                <div class="body">
                   <div class="form-group">
                        <select name="timezone" class="form-control">
                        <?php foreach(tz_list() as $t) { ?>
                            <option value="<?=$t['zone'] ?>" <?=(TIMEZONE_SYSTEM==$t['zone'])?"selected":""?>>
                                <?=$t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                            </option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="input-group text-center">
                        <button type="button" class="btn bg-light-blue waves-effect btnActionUpdate"><?=l('Save')?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>