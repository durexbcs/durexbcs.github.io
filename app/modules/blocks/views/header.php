<nav class="navbar">
    <div class="container<?=session("uid")?"-fluid":""?>">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <?php if(session("uid")){?>
            <a href="javascript:void(0);" class="bars"></a>
            <?php }?>
            <a class="navbar-brand text-center" href="<?=PATH?>"><img src="<?=LOGO?>" title="" alt=""></a>
        </div>
        
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav top-menu">
                <?php if(!hashcheck()){?>
                <li><a href="<?=url("payments")?>"><?=l('Pricing')?></a></li>
                <?php }?>
            </ul>
            <ul class="nav navbar-nav top-menu right mr0">
                <li>
                    <div class="btn-group" style="margin-top: 7px; margin-left: 7px;">
                        <button type="button" class="btn btn-white waves-effect bg-white col-black"><?=strtoupper(LANGUAGE)?></button>
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" style="min-width: 65px; text-align: center; margin-top: 0px!important;">
                            <?php if(!empty($lang))
                            foreach ($lang as $row) {
                            ?>
                            <li><a class="waves-effect waves-block p0" href="<?=PATH?>language?l=<?=$row?>"><?=strtoupper($row)?></a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>