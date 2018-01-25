function Page(){
	var self= this;
	var timeout = 0;
    var status = 0;
    var running = 0;
    var el;
    var w = $(window);
    var clock = $('.countDown span');
    var SPINTAX_PATTERN = /\{[^"\r\n\}]*\}/;
    var ItemPost = [];
	this.init= function(){
        self.InstagramAccount();
        self.InstagramPost();
        self.Editor();
        self.Category();

        if($(".logs_load").length > 0){
            self.History();
            $(window).scroll(function() { //detect page scroll
                if($(window).scrollTop() + $(window).height() + 2 >= $(document).height()) { //if user scrolled to bottom of the page
                    self.LoadHistory();
                }
            }); 
        }

        if($('input.tagsinput').length > 0){
            $('input.tagsinput').tagsinput({
                trimValue: true
            });
        }

        $('.form-datetime').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            minDate: moment().format('YYYY-MM-DD 00:00'),
            currentDate: moment().format('YYYY-MM-DD HH:mm'),
        });

        $('.form-date').bootstrapMaterialDatePicker({
            time: false,
            currentDate: moment().format('YYYY-MM-DD'),
        });

        if($('.js-dataTable').length > 0 || $('.js-dataTableSchedule').length > 0 || $('.js-dataTableScheduleAjax').length > 0 || $('.js-dataTableLogsAjax').length > 0){
            _dataTable = $('.js-dataTable').DataTable({
                paging: false,
                columnDefs: [ {
                    targets: 0,
                    orderable: false
                }],
                aaSorting: [],
                language: {
                    search: 'Search ',
                },
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: false,
                responsive: true,
                emptyTable: Lang['emptyTable']
            });

            $('.filter_account,.filter_profile,.filter_group,.filter_page,.filter_friend').change( function() {
                _dataTable.draw();
            });

            _dataTableSchedule = $('.js-dataTableSchedule').DataTable({
                paging: true,
                pageLength: 50,
                lengthMenu: [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]],
                columnDefs: [ {
                    targets: 0,
                    orderable: false
                }],
                aaSorting: [],
                language: {
                    search: 'Search ',
                },
                bFilter: true,
                bInfo: true,
                bAutoWidth: false,
                responsive: true,
                pagingType: "full_numbers",
                emptyTable: Lang['emptyTable']
            });

            $('.filter_account').change( function() {
                _dataTableSchedule.draw();
            });

            _dataTableScheduleAjax = $('.js-dataTableScheduleAjax').DataTable({
                processing: true,
                serverSide: true,
                columnDefs: [ {
                    targets: 0,
                    orderable: false
                }],
                ajax: $.fn.dataTable.pipeline( {
                    url: CURRENT_URL+'/ajax_page',
                    pages: 1 // number of pages to cache
                }),
                paging: true,
                pageLength: 50,
                lengthMenu: [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]],
                
                aaSorting: [],
                language: {
                    search: 'Search ',
                },
                bFilter: true,
                bInfo: true,
                bAutoWidth: false,
                responsive: true,
                pagingType: "full_numbers",
                emptyTable: Lang['emptyTable']
            });

            $('.filter_account').change( function() {
                _dataTableScheduleAjax.draw();
            });

            //CUSTOM FILTER
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var el_profile = $('.filter_profile');
                    var el_group   = $('.filter_group');
                    var el_page    = $('.filter_page');
                    var el_friend  = $('.filter_friend');
                    var fbuser     = $('.filter_account').val();
                    var profile    = el_profile.is(':checked')?"profile":"";
                    var group      = el_group.is(':checked')?"group":"";
                    var page       = el_page.is(':checked')?"page":"";
                    var friend     = el_friend.is(':checked')?"friend":"";
                    var _account   = data[1];
                    var _type      = data[3];

                    if(fbuser != "" && fbuser != undefined){
                        if(el_profile.length > 0 || el_friend.length > 0){
                            if ((fbuser == _account) && (profile == _type || group == _type || page == _type || friend == _type)){
                                return true;
                            }
                        }else{
                            if (fbuser == _account){
                                return true;
                            }
                        }
                        return false; 
                    }else{
                        if(el_profile.length > 0 || el_friend.length > 0){
                            if (profile == _type || group == _type || page == _type || friend == _type){
                                return true;
                            }
                            return false; 
                        }
                        return true;
                    }
                    
                }
            );
        }

        $('[data-toggle="tooltip"]').tooltip();
        $("[data-toggle=popover]").popover(); 

        $(document).on('click', '.checkAll', function(){
            _that = $(this);
            if(_that.is(":checked")){
                $('.checkItem').prop('checked', true);
            }else{
                $('.checkItem').prop('checked', false);
            }
        });

        $(document).on('click', '.open-schedule', function(){
            _that = $(this);
            _box_schedule = $('.box-post-schedule');
            if(_that.hasClass('active')){
                _box_schedule.hide();
                _that.removeClass('active');
            }else{
                _box_schedule.show();
                _that.addClass('active');
            }
        });

        $(document).on('click', '.btnActionModule', function(){
            _that     = $(this);
            _type     = _that.data("action");
            _category = _that.data("categoty");
            _form     = _that.closest("form");
            _action   = _form.attr("action");
            _redirect = _form.data("redirect");
            _data     = _form.serialize();
            _data     = _data + '&' + $.param({token:token, action: _type, category: _category});
            _confirm = _that.data("confirm");
            _valid   = $('.checkItem:checkbox:checked').length;
            if(_valid > 0 || _type == "delete_all"){
                if(_type == "delete" || _type == "delete_all"){
                    self.showConfirmMessage(_confirm, function(){
                        $.post(_action, _data, function(result){
                            setTimeout(function(){
                                window.location.reload();
                            },2000);
                            self.showSuccessAutoClose(Lang["deleted"], "success", 2000);
                        },'json');
                    });
                }else{
                    $.post(_action, _data, function(result){
                        window.location.reload();
                    },'json');
                }
            }else{
                self.showSuccessAutoClose(Lang["selectoneitem"], "info", 2000);
            }
            
            return false;
        });

        $(document).on('change', '.activity_speed', function(){
            _that = $(this);
            switch(_that.val()){
                case "1":
                    speed = activity_speed[0];
                    break;
                case "2":
                    speed = activity_speed[1];
                    break;
                case "3":
                    speed = activity_speed[2];
                    break;
            }
            $(".repeat_like").val(speed['like']);
            $(".repeat_comment").val(speed['comment']);
            $(".repeat_deletemedia").val(speed['deletemedia']);
            $(".repeat_follow").val(speed['follow']);
            $(".repeat_followback").val(speed['followback']);
            $(".repeat_unfollow").val(speed['unfollow']);
            $(".repeat_repost").val(speed['repost']);
            $(".repeat_delay").val(speed['delay']);
        }); 

        $(document).on('click', '.btnDisconnect', function(){
            _that    = $(this);
            _item  = _that.parents(".item");
            _action  = _item.data("action");
            _confirm = _that.data("confirm");
            _id      = _item.data("id");
            
            _data    = $.param({token:token, id: _id});
            self.showConfirmMessage(_confirm, function(){
                $.post(_action, _data, function(result){
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                    self.showSuccessAutoClose(result['txt'], "success", 2000);
                },'json');
            });
        });

        $(document).on('click', '.btnActionModuleItem', function(){
            _that    = $(this);
            _tr  = _that.parents("tr");
            if(_tr.hasClass("child")){
                _tr = _tr.prev();
            }
            _action  = _tr.data("action");
            _type    = _that.data("action");
            _confirm = _that.data("confirm");
            _id      = _tr.data("id");
            
            if(_type == "delete"){
                _data    = $.param({token:token, action: _type, id: _id});
                self.showConfirmMessage(_confirm, function(){
                    $.post(_action, _data, function(result){
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                        self.showSuccessAutoClose(Lang["deleted"], "success", 2000);
                    },'json');
                });
            }else{
                _type  = (_that.is(":checked"))?"active":"disable";
                _data    = $.param({token:token, action: _type, id: _id});
                $.post(_action, _data, function(result){
                    //window.location.reload();
                },'json');
            }
        });

        $(document).on('click', '.btnUpdateGroups', function(){
            _that    = $(this);
            _action  = _that.data("action-groups");
            _type    = _that.data("type");
            _id      = _that.data("id");
            _data    = $.param({token:token, type: _type, id: _id});
            $(".page-loader-action").fadeIn();
            $.post(_action, _data, function(result){
                self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                $(".page-loader-action").fadeOut();
            },'json');
        });

        $(document).on('click', '.btnActionUpdate', function(){
            _that    = $(this);
            _form     = _that.closest("form");
            _action   = _form.attr("action");
            _redirect = _form.data("redirect");
            _data     = _form.serialize();
            _data     = _data + '&' + $.param({token:token});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $.post(_action, _data, function(result){
                    self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    _form.removeClass('disable');
                    $(".page-loader-action").fadeOut();
                    if(result['st'] == "success")
                        window.location.assign(_redirect);
                },'json');
            }
            return false;
        });

        $(document).on('change', '.logs_accounts, .form-filter', function(){
            $(this).parents('form').submit();
        });

        $(document).on('click', '.btnActivityAll', function(){
            _that = $(this);
            _action = _that.parents(".SchedulesListActivity").data("action");
            _item = _that.parents(".item");
            _id   = _item.data("id");
            _data = $.param({token:token, id: _id});
            if(!_that.hasClass('disabled')){
                _that.addClass('disabled');
                $(".ajax_data_search").html("");
                self.getLoading(".ajax_data_search");
                $.post(_action, _data, function(result){
                    _item.find(".ajax_status").html(result['status']);
                    _item.find(".ajax_btn_enable").html(result['btn']);
                    _that.removeClass('disabled');
                    self.delLoading();
                },'json');
            }
        });

        $(document).on('click', '.btnActionSearch', function(){
            _that    = $(this);
            _form     = _that.closest("form");
            _action   = _form.attr("action");
            _data     = _form.serialize();
            _data     = _data + '&' + $.param({token:token});
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $(".ajax_data_search").html("");
                self.getLoading(".ajax_data_search");
                $.post(_action, _data, function(result){
                    if(result['st'] == 'success'){
                        _html = jQuery.parseJSON(result['result']);
                        $(".ajax_data_search").html(_html);
                    }else{
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _form.removeClass('disable');
                    self.delLoading();
                },'json');
            }
            return false;
        });

        $(document).on('click', '.btnDeleteAllItem', function(){
            _that = $(this);
            _that.parents('.vttags').find('.item').remove();
        });

        $(document).on('click', '.btnOpenAddTags', function(){
            _that    = $(this);
            _data    = $.param({token:token});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $( "#PopupAddTags" ).remove();
                $.post(PATH+"search/ajax_open_search_tags", _data, function(result){
                    $("body").append(result);
                    $( "#PopupAddTags" ).modal('show'); 
                    _that.removeClass('disable');
                });
            }
            return false;
        });

        $(document).on('click', '.btnSearchTags', function(){
            _that    = $(this);
            _tag     = $(".popup_tag").val();  
            _account = _that.parents(".formSearchPopup").find('select').val();
            _data    = $.param({token:token, tag: _tag, account: _account});
            self.getLoading(".ajax_dataSearchTag");
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH+"search/search_tag", _data, function(result){
                    if(result['st'] == 'success'){
                        _html = jQuery.parseJSON(result['result']);
                        _that.parents(".modal").find(".ajax_dataSearchTag").html(_html);
                    }else{
                        self.delLoading();
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _that.removeClass('disable');
                }, 'json');
            }
            return false;
        });

        $(document).on('click', '.btnAddTags', function(){
            $( ".ajax_dataSearchTag .item" ).each(function( index ) {
                _newTags = $(this);
                _checkTag = false;
                if(_newTags.find('input:checked').length > 0){
                    $( ".list-tags .item" ).each(function( index ) {
                        _oldTag = $(this);
                        if(_oldTag.data('tag') == _newTags.data('tag')){
                            _checkTag = true;
                        }
                    });
                    if(!_checkTag){
                        $('<div class="item" data-tag="'+ $( this ).data('tag') +'">'+ $( this ).data('tag') +'<input type="hidden" name="tags[]" value="'+ $( this ).data('tag') +'"><div class="icon-remove btnRemoveTag">x</div><div class="icon-tag"></div></div>').insertBefore( ".actionAddTags" );
                    }
                }
            });

            _listTags = $(".popup_list_tags").val();
            _listTags = _listTags.split(",");
            for (var i = 0; i < _listTags.length; i++) {
                _listTags[i]
                _checkTag = false;
                if(_listTags[i] != ""){
                    $( ".list-tags .item" ).each(function( index ) {
                        _oldTag = $(this);
                        if(_oldTag.data('tag') == _listTags[i]){
                            _checkTag = true;
                        }
                    });
                    if(!_checkTag){
                        $('<div class="item" data-tag="'+ _listTags[i] +'">'+ _listTags[i] +'<input type="hidden" name="tags[]" value="'+ _listTags[i] +'"><div class="icon-remove btnRemoveTag">x</div><div class="icon-tag"></div></div>').insertBefore( ".actionAddTags" );
                    }
                }
            }
            return false;
        });

        $(document).on("click", ".btnRemoveTag", function(){
            $(this).parents(".item").remove();
        });

        $(document).on('click', '.btnOpenAddComments', function(){
            _that    = $(this);
            _data     = $.param({token:token});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $( "#PopupAddComments" ).remove();
                $.post(PATH+"search/ajax_open_add_comments", _data, function(result){
                    $("body").append(result);
                    $( "#PopupAddComments" ).modal('show'); 
                    _that.removeClass('disable');
                });
            }
            return false;
        });

        $(document).on('click', '.btnAddComnents', function(){
            _listTags = $(".popup_list_comments").val();
            _listTags = _listTags.split(",");
            for (var i = 0; i < _listTags.length; i++) {
                _listTags[i]
                _checkTag = false;
                if(_listTags[i] != ""){
                    $( ".list-comments .item" ).each(function( index ) {
                        _oldTag = $(this);
                        if(_oldTag.data('tag') == _listTags[i]){
                            _checkTag = true;
                        }
                    });
                    if(!_checkTag){
                        $('<div class="item" data-tag="'+ _listTags[i] +'">'+ _listTags[i] +'<input type="hidden" name="comments[]" value="'+ _listTags[i] +'"><div class="icon-remove btnRemoveTag">x</div><div class="icon-tag"></div></div>').insertBefore( ".actionAddComments" );
                    }
                }
            }
            return false;
        });

        $(document).on('click', '.btnOpenAddLocations', function(){
            _that    = $(this);
            _data     = $.param({token:token});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $( "#PopupAddLocations" ).remove();
                $.post(PATH+"search/ajax_open_search_locations", _data, function(result){
                    $("body").append(result);
                    $( "#PopupAddLocations" ).modal('show'); 
                    _that.removeClass('disable');
                });
            }
            return false;
        });

        $(document).on('click', '.btnSearchLocations', function(){
            _that     = $(this);
            _lat      = $("[name='lat']").val();
            _lng      = $("[name='lng']").val();
            _name     = $(".popup_location").val();  
            _account  = _that.parents(".formSearchPopup").find('select').val();
            _data     = $.param({token:token, keyword: _name, lat: _lat, lng: _lng, account: _account});
            self.getLoading(".ajax_dataSearchLocation");
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH+"search/search_location", _data, function(result){
                    if(result['st'] == 'success'){
                        _html = jQuery.parseJSON(result['result']);
                        _that.parents(".modal").find(".ajax_dataSearchLocation").html(_html);
                    }else{
                        self.delLoading();
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _that.removeClass('disable');
                }, 'json');
            }
            return false;
        });

        $(document).on('click', '.btnAddLocations', function(){
            $( ".ajax_dataSearchLocation .item" ).each(function( index ) {
                _newTags = $(this);
                _checkTag = false;
                if(_newTags.find('input:checked').length > 0){
                    $( ".list-locations .item" ).each(function( index ) {
                        _oldTag = $(this);
                        if(_oldTag.data('location') == _newTags.data('location')){
                            _checkTag = true;
                        }
                    });
                    if(!_checkTag){
                        $('<div class="item" data-location="'+ $( this ).data('location') +'">'+ $( this ).data('name') +'<input type="hidden" name="locations[]" value="'+ $( this ).data('location') +'"><div class="icon-remove btnRemoveTag">x</div><div class="icon-tag"></div></div>').insertBefore( ".actionAddLocations" );
                    }
                }
            });
            return false;
        });

        $(document).on('click', '.btnOpenAddUsernames', function(){
            _that    = $(this);
            
            _data     = $.param({token:token});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $( "#PopupAddUsernames" ).remove();
                $.post(PATH+"search/ajax_open_search_usernames", _data, function(result){
                    $("body").append(result);
                    $( "#PopupAddUsernames" ).modal('show'); 
                    _that.removeClass('disable');
                });
            }
            return false;
        });

        $(document).on('click', '.btnSearchUsernames', function(){
            _that     = $(this);
            _account  = _that.parents(".formSearchPopup").find('select').val();
            _username = $(".popup_username").val();  
            _data     = $.param({token:token, username: _username, account: _account});
            self.getLoading(".ajax_dataSearchUsername");
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH+"search/search_username", _data, function(result){
                    if(result['st'] == 'success'){
                        _html = jQuery.parseJSON(result['result']);
                        _that.parents(".modal").find(".ajax_dataSearchUsername").html(_html);
                    }else{
                        self.delLoading();
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _that.removeClass('disable');
                }, 'json');
            }
            return false;
        });

        $(document).on('click', '.btnAddUsernames', function(){
            $( ".ajax_dataSearchUsername .item" ).each(function( index ) {
                _newTags = $(this);
                _checkTag = false;
                if(_newTags.find('input:checked').length > 0){
                    $( ".list-usernames .item" ).each(function( index ) {
                        _oldTag = $(this);
                        if(_oldTag.data('username') == _newTags.data('username')){
                            _checkTag = true;
                        }
                    });
                    if(!_checkTag){
                        $('<div class="item" data-username="'+ $( this ).data('username') +'">'+ $( this ).data('username') +'<input type="hidden" name="usernames[]" value="'+ $( this ).data('id') + "|" + $( this ).data('username') +'"><div class="icon-remove btnRemoveTag">x</div><div class="icon-tag"></div></div>').insertBefore( ".actionAddUsernames" );
                    }
                }
            });
            return false;
        });


        $(document).on('click', '.btnOpenAddMessages', function(){
            _that    = $(this);
            _data     = $.param({token:token});
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $( "#PopupAddMessages" ).remove();
                $.post(PATH+"search/ajax_open_add_messages", _data, function(result){
                    $("body").append(result);
                    $( "#PopupAddMessages" ).modal('show'); 
                    _that.removeClass('disable');
                });
            }
            return false;
        });

        $(document).on('click', '.btnAddMessages', function(){
            _listTags = $(".popup_list_messages").val();
            _listTags = _listTags.split(",");
            for (var i = 0; i < _listTags.length; i++) {
                _listTags[i]
                _checkTag = false;
                if(_listTags[i] != ""){
                    $( ".list-messages .item" ).each(function( index ) {
                        _oldTag = $(this);
                        if(_oldTag.data('message') == _listTags[i]){
                            _checkTag = true;
                        }
                    });
                    if(!_checkTag){
                        $('<div class="item" data-message="'+ _listTags[i] +'">'+ _listTags[i] +'<input type="hidden" name="messages[]" value="'+ _listTags[i] +'"><div class="icon-remove btnRemoveTag">x</div><div class="icon-tag"></div></div>').insertBefore( ".actionAddMessages" );
                    }
                }
            }
            return false;
        });
	};

    this.History = function(){
        self.LoadHistory();
    }

    this.LoadHistory = function(){
        _that    = $('.logs_load');
        _page    = _that.data("page");  
        _type    = _that.data("type");  
        _action  = _that.data("action");
        _account = $('.logs_accounts').val();  
        _data    = $.param({token:token, page: _page, type: _type, account: _account});
        if(_page != -1){
            self.delLoading();
            self.getLoading(".logs_load");
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(_action, _data, function(result){
                    _html = jQuery.parseJSON(result['result']);
                    $(_html).insertBefore( ".logs_load");
                    _that.data("page", result['page']);
                    _that.removeClass('disable');
                    self.delLoading();
                }, 'json');
            }
        }
        return false;
    }

    this.Editor = function(){
        $('.dialog-upload').click(function() {
            var _that = $(this);
            var fm = $('<div/>').dialogelfinder({
                url : BASE+'assets/plugins/elfinder/php/connector.php',
                lang : 'en',
                width : ($(window).width() > 840)?840:$(window).width() - 30,
                resizable: false,
                destroyOnClose : true,
                getFileCallback : function(files, fm) {
                    _that.parents(".input-group").find("input").val(files.url);
                    _type = $('.post_type .active').data("type");
                    switch(_type){
                        case "photo":
                            $(".preview-box-3 .preview-box-image").css('background-image', 'url(' + self.spintax(files.url) + ')')
                            break;
                    }
                },
                commandsOptions : {
                    getfile : {
                        oncomplete : 'close',
                        folders : true
                    }
                }
            }).dialogelfinder('instance');
        });


        $("[name='image_url']").keyup(function(){
            _that = $(this);
            $image = _that.val();
            if($image != ""){
                $(".preview-box-3 .preview-box-image").css('background-image', 'url(' + self.spintax($image) + ')')
            }else{
                $(".preview-box-3 .preview-box-image").removeAttr("style");
            }
        });

        $(document).on("click", ".post_type li", function(){
            $(".preview-box").hide();
            $(".data-message, .preview-time, .preview-comment").show();

            _type = $('.post_type .active').data("type");
            switch(_type){
                case "story":
                    $(".data-message, .preview-time, .preview-comment").hide();
                    $(".preview-box-2").show();
                    break;

                case "photo":
                    $(".preview-box-3").show();
                    break;

                case "video":
                    $(".preview-box-4").show();
                    break;
            }
        });

        $(document).on("click", ".btn-modal-save", function(){
            $('.btnSavePost').trigger("click");
        });

        $('.btnSavePost').click(function(){
            _that     = $(this);
            _form     = _that.closest(".formSchedule");
            _data     = _form.serialize();
            _type     = $('.post_type .active').data('type');
            _title    = $(".save_title").val();
            _category = _that.data("type");
            _data     = _data + '&' + $.param({token:token, title: _title, type: _type, category: _category});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');

                $.post(PATH + "save/ajax_save", _data, function(result){
                    if(result.st == "error"){
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                        _form.removeClass('disable');
                    }else if(result.st == "title"){
                        $('#modal-save').modal('toggle');
                    }else{
                        $(".save_title").val("");
                        $('#modal-save').modal('hide');
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _form.removeClass('disable');
                    $(".page-loader-action").fadeOut();
                },'json');
            }

            return false;
        });

        $(document).on("change", ".getSavePost", function(){
            _that = $(this);
            _value = _that.val();
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "save/ajax_get_save", {token: token, value: _value}, function(data){
                    _that.removeClass('disable');
                    if(data != "" && data != null){
                        switch(data.category){
                            case "post":
                                el[0].emojioneArea.setText(data.message);
                                if(data.type == "video"){
                                    $("[name="+data.type+"_url]").val(data.image).trigger("keyup");
                                }else{
                                    $("[name=image_url]").val(data.image).trigger("keyup");
                                }
                                $("li[data-type='"+data.type+"'] a").trigger("click");
                                break;

                            default:
                                el[0].emojioneArea.setText(data.message);
                                $("[name=link]").val(data.url).trigger("keyup");
                                break;
                        }
                    }
                },'json');
            }
        });

        if($('.post-message').length > 0){
            el = $(".post-message").emojioneArea({
                hideSource: true,
                useSprite: false,
                pickerPosition    : "bottom",
                filtersPosition   : "bottom",
            });

            el[0].emojioneArea.on("keyup", function(editor) {
                _data = editor.html();
                _type = $('.post_type .active').data("type");
                if($(".data-message").length > 0){
                    if(_data != ""){
                        $(".data-message").html("<b>"+Lang["Anonymous"]+"</b> " + _data);
                    }else{
                        $(".data-message").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                    }
                }else{
                    _el = $(".data-message-content");
                    if(_data != ""){
                        _el.show()
                    }else{
                        _el.hide();
                    }
                    _el.html(_data);
                }
            });

            el[0].emojioneArea.on("change", function(editor) {
                _data = editor.html();
                _type = $('.post_type .active').data("type");
                if($(".data-message").length > 0){
                    if(_data != ""){
                        $(".data-message").html("<b>Anonymous</b> " + _data);
                    }else{
                        $(".data-message").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                    }
                }else{
                    _el = $(".data-message-content");
                    if(_data != ""){
                        _el.show()
                    }else{
                        _el.hide();
                    }
                    _el.html(_data);
                }
            });

            el[0].emojioneArea.on("emojibtn.click", function(editor) {
                _data = $(".emojionearea-editor").html();
                _type = $('.post_type .active').data("type");
                if($(".data-message").length > 0){
                    if(_data != ""){
                        $(".data-message").html(_data);
                    }else{
                        $(".data-message").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                    }
                }else{
                    _el = $(".data-message-content");
                    if(_data != ""){
                        _el.show()
                    }else{
                        _el.hide();
                    }
                    _el.html(_data);
                }
            });
        }
    }

    this.InstagramAccount = function(){
        $(document).on("click", ".btnIGAccountUpdate", function(){
            _that     = $(this);
            _form     = _that.closest("form");
            _action   = _form.attr("action");
            _redirect = _form.data("redirect");
            _data     = _form.serialize();
            _data     = _data + '&' + $.param({token:token});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $.post(_action, _data, function(result){
                    _form.removeClass('disable');
                    $(".page-loader-action").fadeOut();
                    if(result['st'] == "success"){
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                        window.location.assign(_redirect);
                    }else{
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                },'json');
            }

            return false;
        });
    }

    this.InstagramPost = function(){
        $(document).on("click", ".btnPostnow", function(){
            self.Postnow($(this));
        });

        $(document).on("click", ".btnResumePost", function(){
            clock.countdown('resume');
            self.Postnow($(this));
        });

        $(document).on("click", ".btnPausePost", function(){
            clock.countdown('pause');
            clearTimeout(timeout);
        });

        $(document).on("click", ".btnSaveSchedules", function(){
            _that     = $(this);
            _form     = _that.closest("form");
            _action   = _form.data("action");
            _type     = $('.post_type .active').data('type');
            _data     = _form.serialize();
            _data     = _data + '&' + $.param({token:token, type: _type});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $.post(_action, _data, function(result){
                    if(result.st == 'valid'){
                        self.showNotification(result.label, result.txt, 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }else{
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                        self.showSuccessAutoClose(result.txt, "success", 2000);
                        $(".page-loader-action").fadeOut();
                    }
                    $(".page-loader-action").fadeOut();
                    _form.removeClass('disable');
                },'json');
            }
        });

        $(document).on("click", ".btnAddSchedules", function(){
            _that     = $(this);
            _form     = _that.closest("form");
            _action   = _form.data("action");
            _type     = _form.data('type');
            _redirect = _form.data("redirect");
            _data     = _form.serialize();
            _data     = _data + '&' + $.param({token:token, type: _type});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $.post(_action, _data, function(result){
                    if(result.st == 'valid'){
                        self.showNotification(result.label, result.txt, 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }else{
                        setTimeout(function(){
                            window.location.assign(_redirect);
                        },2000);
                        self.showSuccessAutoClose(result.txt, "success", 2000);
                        $(".page-loader-action").fadeOut();
                    }
                    $(".page-loader-action").fadeOut();
                    _form.removeClass('disable');
                },'json');
            }
        });
        
    }

    this.Postnow = function(_that){
        _form     = _that.closest("form");
        _action   = _form.attr("action");
        _redirect = _form.data("redirect");
        _type     = $('.post_type .active').data('type');
        _data     = _form.serialize();
        _deplay   = $('.deplay_post_now').val();
        _group    = "";
        _item     = "";
        _stop     = false;

        $(".js-dataTable tbody tr").each(function(index,value){
            _tr   = $(this);
            if(_tr.hasClass('post-pending') && _tr.find(".checkItem").is(":checked")){
                running = 1;
                if(!_stop){
                    ItemPost.push(_tr);
                    _item  = _tr;
                    _group = _tr.find(".checkItem").val();
                    _stop  = true;
                }
            }
        });
        
        _data     = _data + '&' + $.param({token:token, type: _type, group: _group});
        if(_group != ""){
            ItemPost[ItemPost.length-1].removeClass("post-pending").addClass("post-processing");
            //_item.removeClass("post-pending").addClass("post-processing");
            _item.find(".status-post").html(Lang['processing']);
            $.post(_action, _data, function(result){
                if(result.st == 'valid'){
                    self.showNotification(result.label, result.txt, 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    ItemPost[0].removeClass("post-processing").addClass("post-pending");
                    ItemPost[0].find(".status-post").html('');
                    clearTimeout(timeout);
                }else{
                    count_process = $(".post-pending input:checkbox:checked").length;
                    clock.countdown(self.getMinutes(count_process*_deplay), function(event) {
                        $(this).html(event.strftime('%H:%M:%S'));
                    });
                    ItemPost[0].removeClass("post-processing").addClass("post-"+result.st);
                    ItemPost[0].find(".status-post").html(result.txt);
                }
                ItemPost.shift();
            },'json');

            timeout = setTimeout(function(){
                self.Postnow(_that);
            },_deplay*1000);
        }
    }

    this.Category = function(){
        $(document).on("click", ".btn-modal-add-category", function(){
            $('.btnAddCategory').trigger("click");
        });

        $(document).on("click", ".btnAddCategory", function(){
            _that     = $(this);
            _form     = _that.closest("form");
            _data     = _form.serialize();
            _title    = $(".category_title").val();
            _category = _that.data("type");
            _data     = _data + '&' + $.param({token:token, title: _title, category: _category});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $.post(PATH + "category/ajax_add_category", _data, function(result){
                    if(result.st == "error"){
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }else if(result.st == "title"){
                        $('#modal-category').modal('toggle');
                    }else{
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                        $(".category_title").val("");
                        $('#modal-category').modal('hide');
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _form.removeClass('disable');
                    $(".page-loader-action").fadeOut();
                },'json');
            }
        });

        $(document).on("click", ".btn-modal-update-category", function(){
            $('.btnUpdateCategory').trigger("click");
        });

        $(document).on("click", ".btnUpdateCategory", function(){
            _that     = $(this);
            _form     = _that.closest("form");
            _data     = _form.serialize();
            _cid      = $(".category_id").val();
            _data     = _data + '&' + $.param({token:token, cid: _cid});
            $(".page-loader-action").fadeIn();
            if(!_form.hasClass('disable')){
                _form.addClass('disable');
                $.post(PATH + "category/ajax_update_category", _data, function(result){
                    if(result.st == "error"){
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }else if(result.st == "id"){
                        $('#modal-update-category').modal('toggle');
                    }else{
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                        $('#modal-update-category').modal('hide');
                        self.showNotification(result['label'], result['txt'], 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
                    }
                    _form.removeClass('disable');
                    $(".page-loader-action").fadeOut();
                },'json');
            }
        });

        $(document).on("change", ".categories", function(){
            _that  = $(this);
            _id    = _that.val();
            _data  = $.param({token:token, id: _id});
            $.post(PATH + "category/ajax_get_category", _data, function(result){
                window.location.reload();
            });
        });

        $(document).on("click", ".btnDeleteCategory", function(){
            _that  = $(this);
            _id    = $(".categories").val();
            _data  = $.param({token:token, id: _id});
            $(".page-loader-action").fadeIn();
            if(!_that.hasClass('disable')){
                _that.addClass('disable');
                $.post(PATH + "category/ajax_delete_category", _data, function(result){
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                    $(".page-loader-action").fadeOut();
                    self.showSuccessAutoClose(result['txt'], "success", 2000);
                },'json');
            }
        });
    }
    
    this.showNotification = function(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
        if (colorName === null || colorName === '') { colorName = 'bg-black'; }
        if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
        if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
        if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
        var allowDismiss = true;

        $.notify({
            message: text
        },
            {
                type: colorName,
                allow_dismiss: allowDismiss,
                newest_on_top: true,
                timer: 1000,
                placement: {
                    from: placementFrom,
                    align: placementAlign
                },
                animate: {
                    enter: animateEnter,
                    exit: animateExit
                },
                template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">x</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
            });
    };

    this.ExportTable = function(element) {
        $(element).DataTable({
            paging: false,
            columnDefs: [ {
                targets: 0,
                orderable: false
            }],
            aaSorting: [],
            language: {
                search: 'Search ',
            },
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: false,
            responsive: true,
            emptyTable: Lang['emptyTable'],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'print'
            ]
        });
    }

    this.getLoading = function(element){
        _html = '<div class="custom-loading"><div class="md-preloader pl-size-md"><svg viewbox="0 0 75 75"><circle cx="37.5" cy="37.5" r="33.5" class="pl-blue-grey" stroke-width="5" /></svg></div></div>';
        $(element).append(_html);
    }

    this.delLoading = function(element){
        $(".custom-loading").remove();
    }

    this.getMinutes = function(seconds){
        return new Date(new Date().valueOf() + seconds * 1000);
    }

    this.cutText = function(text, number){
        if(text.length > number){
            return text.substring(0, number)+"...";
        }else{
            return text;
        }
    }

    this.getLocation = function(){
        if (navigator.geolocation) {
            location = navigator.geolocation.getCurrentPosition(callback);
        } else { 
            self.showNotification("lable-red", "Can't get your location", 'bottom', 'center', 'animated bounceIn', 'animated bounceOut');
        }
    }


    this.spintax = function (spun) {
        var match;
        while (match = spun.match(SPINTAX_PATTERN)) {
            match = match[0];
            var candidates = match.substring(1, match.length - 1).split("|");
            spun = spun.replace(match, candidates[Math.floor(Math.random() * candidates.length)])
        }
        return spun;
    }

    this.showConfirmMessage = function($message, $function) {
        swal({
            title: $message,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: Lang["yes"],
            closeOnConfirm: false
        }, $function);
    }

    this.showSuccessAutoClose = function($message, $label, $timeout) {
        swal({
            title: $message,
            type: $label,
            timer: $timeout,
            closeOnConfirm: false,
            showConfirmButton: false
        });
    }
}
Page= new Page();
$(function(){
	Page.init();
});

$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'GET' // Ajax HTTP method
    }, opts );
 
    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
 
    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }
         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }
                     
                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );