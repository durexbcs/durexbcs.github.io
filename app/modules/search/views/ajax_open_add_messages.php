<div class="modal fade" id="PopupAddMessages" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-grey">
                <h4 class="modal-title" id="defaultModalLabel"><?=l('Add Messages')?></h4>
            </div>
            <div class="modal-body pt0">
                <div class="form-group">
                    <div class="form-line box_popup_comments p15">
                        <textarea rows="4" class="form-control no-resize popup_list_messages" placeholder="<?=l('message a,message b,message c,...')?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #F5F5F5;">
                <button type="button" class="btn bg-light-green waves-effect btnAddMessages"><?=l('Add Messages')?></button>
                <button type="button" class="btn bg-grey waves-effect" data-dismiss="modal"><?=l('Close')?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $(".popup_list_messages").emojioneArea({
        hideSource: true,
        useSprite: false,
        pickerPosition    : "bottom",
        filtersPosition   : "bottom",
    });
  });
</script>