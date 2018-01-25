<div class="modal fade" id="PopupAddTags" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-grey">
                <h4 class="modal-title" id="defaultModalLabel"><?=l('Add Tags')?></h4>
            </div>
            <div class="modal-body pt0">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#home" data-toggle="tab" aria-expanded="false"><?=l('Search Tag')?> </a></li>
                    <li role="presentation" class=""><a href="#profile" data-toggle="tab" aria-expanded="true"><?=l('Add Multi Tags')?></a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="home">
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
                                <input type="text" name="popup_tag" class="form-control popup_tag" placeholder="<?=l('Hashtag')?>">
                            </div>
                            <span class="input-group-btn">
                              <a class="btn bg-grey waves-effect btnSearchTags"><i class="fa fa-search" aria-hidden="true"></i> <?=l('Search')?></a>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ajax_dataSearchTag">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" class="form-control no-resize popup_list_tags" placeholder="<?=l('taga,tagb,tagc,...')?>"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #F5F5F5;">
                <button type="button" class="btn bg-light-green waves-effect btnAddTags"><?=l('Add Tags')?></button>
                <button type="button" class="btn bg-grey waves-effect" data-dismiss="modal"><?=l('Close')?></button>
            </div>
        </div>
    </div>
</div>