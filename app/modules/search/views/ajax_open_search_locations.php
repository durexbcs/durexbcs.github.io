<div class="modal fade" id="PopupAddLocations" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form class="location">
            <div class="modal-content">
                <div class="modal-header bg-grey">
                    <h4 class="modal-title" id="defaultModalLabel"><?=l('Add Locations')?></h4>
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
                            <input type="text" name="popup_location" class="form-control popup_location" placeholder="<?=l('Enter location name')?>">
                        </div>
                        <span class="input-group-btn">
                          <a class="btn bg-grey waves-effect btnSearchLocations"><i class="fa fa-search" aria-hidden="true"></i> <?=l('Search')?></a>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="map_canvas" style="width: 100%; height: 250px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input name="formatted_address" type="hidden" value="">
                            <input name="lat" type="hidden" value="">
                            <input name="lng" type="hidden" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ajax_dataSearchLocation">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #F5F5F5;">
                    <button type="button" class="btn bg-light-green waves-effect btnAddLocations"><?=l('Add Locations')?></button>
                    <button type="button" class="btn bg-grey waves-effect" data-dismiss="modal"><?=l('Close')?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        var options = {
            details: "form.location",
            map: ".map_canvas"
        };
        
        setTimeout(function(){
            $(".popup_location").geocomplete(options).bind("geocode:result", function(event, result){});
        },1000);
        
        $(".btnSearchLocations").click(function(){
            $("#geocomplete").trigger("geocode");
            $(window).resize();
        });
    });
</script>