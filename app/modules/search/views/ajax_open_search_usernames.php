<div class="modal fade" id="PopupAddUsernames" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-grey">
                <h4 class="modal-title" id="defaultModalLabel"><?=l('Add Usernames')?></h4>
            </div>
            <div class="modal-body pt0">
               <div class="input-group mb0 formSearchPopup">
                    <span class="input-group-btn">
                      <select name="account" class="form-control account" style="min-width: 120px;">
                        <?php if(!empty($accounts)){
                        foreach ($accounts as $row) {
                        ?>
                          <option value="<?=$row->id?>"><?=$row->username?></option>
                        <?php }}?>
                      </select>
                    </span>
                    <div class="form-line">
                        <input type="text" name="popup_username" class="form-control popup_username" placeholder="<?=l('Username')?>">
                    </div>
                    <span class="input-group-btn">
                      <a class="btn bg-grey waves-effect btnSearchUsernames"><i class="fa fa-search" aria-hidden="true"></i> <?=l('Search')?></a>
                    </span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="ajax_dataSearchUsername">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #F5F5F5;">
                <button type="button" class="btn bg-light-green waves-effect btnAddUsernames"><?=l('Add Usernames')?></button>
                <button type="button" class="btn bg-grey waves-effect" data-dismiss="modal"><?=l('Close')?></button>
            </div>
        </div>
    </div>
</div>