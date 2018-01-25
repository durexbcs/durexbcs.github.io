<?php $version = random_string()?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?=$template['title']." - ".TITLE?></title>
    <meta name="description" content="<?=DESCRIPTION?>"/>
    <meta name="keywords" content="<?=KEYWORDS?>"/>
    
    <!-- Facebook open graph tags -->
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="VTCreators"/>
    <meta property="og:url" content="<?=current_url()?>"/>
    <meta property="og:title" content="<?=$template['title']." - ".TITLE?>"/>
    <meta property="og:description" content="<?=DESCRIPTION?>"/>

    <!-- Twitter card tags -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@vtcreators"/>
    <meta name="twitter:title" content="<?=$template['title']." - ".TITLE?>"/>
    <meta name="twitter:description" content="<?=DESCRIPTION?>"/>
    <!-- Favicon-->
    <link rel="icon" href="<?=BASE?>assets/images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="<?=BASE?>assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?=BASE?>assets/css/fonts.css" rel="stylesheet">
    <link href="<?=BASE?>assets/plugins/node-waves/waves.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/jquery.ui/smoothness/jquery-ui-1.10.1.custom.css" rel="stylesheet" >
    <link href="<?=BASE?>assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?=BASE?>assets/plugins/jquery-datatable/extensions/responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="<?=BASE?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/elfinder/css/elfinder.min.css" rel="stylesheet" >
    <link href="<?=BASE?>assets/plugins/material-design-preloader/md-preloader.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/animate-css/animate.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/plugins/emojionearea/emojionearea.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?=BASE?>assets/css/style.css" rel="stylesheet">
    <link href="<?=BASE?>assets/css/themes/all-themes.css" rel="stylesheet" />
    <link href="<?=BASE?>assets/css/custom.css?v=1.4" rel="stylesheet">
    <script src="<?=BASE?>assets/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        var PATH       = '<?=PATH?>';
        var BASE       = '<?=BASE?>';
        var CURRENT_URL= '<?=current_url()?>';
        var list_chart = [];
        var token      = '<?=$this->security->get_csrf_hash();?>';
        var module     = '<?=$this->router->fetch_class()?>';
        var Lang = {};
        Lang["yes"]     = '<?=l('Yes')?>';
        Lang["deleted"] = '<?=l('Deleted')?>';
        Lang["selectoneitem"] = '<?=l('Select at least one item')?>';
        Lang["selectonemedia"] = '<?=l('Select at least one Instagram account')?>';
        Lang["emptyTable"] = '<?=l('No data available in table')?>';
        Lang["processing"] = '<?=l('Processing')?>';
        Lang["Anonymous"] = '<?=l('Anonymous')?>';
    </script>
</head>

<body class="theme-<?=THEME?>">
    <!-- Page Loader -->
    <div class="page-loader-action">
        <div class="loader">
            <div class="md-preloader pl-size-md">
                <svg viewbox="0 0 75 75">
                    <circle cx="37.5" cy="37.5" r="33.5" class="pl-red" stroke-width="4" />
                </svg>
            </div>
            <p><?=l('Please wait...')?></p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <?=modules::run("blocks/header")?>
    <?=modules::run("blocks/sidebar")?>

    <section class="content">
        <div class="container-fluid">
            <?php if(!check_expiration() && IS_ADMIN != 1){?>
            <div class="alert alert-danger">
                <strong><?=l('Notice:')?></strong> <?=l('Out of date! System auto stop all activity on your instagram accounts.')?>
            </div>
            <?php }?>
            <?=$template['body']?>
        </div>
    </section>

    <!-- Add new account -->
    <div class="modal fade" id="modal-add-account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?=modules::run("instagram_accounts/add_account")?>
            </div>
        </div>
    </div>

    <!-- Modal Save-->
    <div class="modal fade" id="modal-how-to-use" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header bg-<?=THEME?>">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?=l('How to use')?></h4>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-red btnCloseModelHowToUse"><?=l('I understand and close it')?></button>
                    </div>
            </div>
        </div>
    </div>

    <!-- Modal Save-->
    <div class="modal fade" id="modal-save" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-blue-grey">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?=l('title')?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control save_title"/>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-modal-save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('save')?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-blue-grey">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?=l('title')?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control category_title"/>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-modal-add-category"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=l('Add new')?></button>
                </div>
            </div>
        </div>
    </div>
     <script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_API_KEY?>&sensor=false&libraries=places" type="text/javascript"></script>
    <script src="<?=BASE?>assets/plugins/geocomplete/jquery.geocomplete.js"></script>
    <script src="<?=BASE?>assets/plugins/bootstrap/js/bootstrap.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery.ui/jquery.ui.min.js"></script>
    <script src="<?=BASE?>assets/plugins/momentjs/moment.js"></script>
    <script src="<?=BASE?>assets/plugins/geocomplete/jquery.geocomplete.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="<?=BASE?>assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="<?=BASE?>assets/plugins/gmaps/gmaps.js"></script>
    <script src="<?=BASE?>assets/plugins/highcharts/highcharts.js"></script>
    <script src="<?=BASE?>assets/plugins/countid/jquery.countdown.min.js"></script>
    <script src="<?=BASE?>assets/plugins/elfinder/js/elfinder.full.js"></script>
    <script src="<?=BASE?>assets/plugins/elfinder/js/jquery.dialogelfinder.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="<?=BASE?>assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script src="<?=BASE?>assets/plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="<?=BASE?>assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="<?=BASE?>assets/plugins/node-waves/waves.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/1.5.0/lib/js/emojione.min.js"></script>
    <script type="text/javascript" src="<?=BASE?>assets/plugins/emojionearea/emojionearea.js"></script>

    <!-- Custom Js -->
    <script src="<?=BASE?>assets/js/admin.js"></script>
    <script src="<?=BASE?>assets/js/analytics.js"></script>
    <script src="<?=BASE?>assets/js/script.js"></script>
</body>

</html>