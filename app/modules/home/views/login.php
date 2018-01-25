<div class="box-login">
  <div class="login-form">
    <ul class="login-nav" >
        <li class="bg-<?=THEME?> left active">
            <a href="<?=url("")?>"><?=l('Login')?></a>
        </li>
        <li class="right bg-<?=THEME?>">
            <a href="<?=url("register")?>"><?=l('Register')?></a>
        </li>
    </ul>
    <div class="clearfix"></div>
    <form action="<?=url('user_management/ajax_login')?>" data-redirect="<?=current_url()?>">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">person</i>
            </span>
            <div class="form-line">
                <input type="text" class="form-control" name="email" placeholder="<?=l('Email')?>" required autofocus>
            </div>
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">lock</i>
            </span>
            <div class="form-line">
                <input type="password" class="form-control" name="password" placeholder="<?=l('Password')?>" required>
            </div>
        </div>
        <div class="input-group">
          <div class="another_action pull-left text-left">
            <input type="checkbox" id="md_checkbox_38" name="remember" class="filled-in chk-col-grey">
            <label for="md_checkbox_38"><?=l('Remember me')?></label><br/>
            <a href="<?=url("forgot_password")?>"><?=l('Forgot password')?></a>
          </div>
          <button type="submit" class="right btn bg-light-green waves-effect btnActionUpdate"><?=l('Login')?></button>
        </div>

        <?php if((FACEBOOK_ID != "" && FACEBOOK_SECRET != "") || (GOOGLE_ID != "" && GOOGLE_SECRET != "") || (TWITTER_ID != "" && TWITTER_SECRET != "")){?>
        <div class="clearfix"></div>
        <div class="login-social">
            <fieldset>
                <legend><span><?=l('OR LOGIN VIA')?></span></legend>
            </fieldset>
            <div class="list-social">
                <?php if(FACEBOOK_ID != "" && FACEBOOK_SECRET != ""){?>
                <a href="<?=url("oauth/facebook")?>" title=""><img src="<?=BASE?>assets/images/btn-facebook.png" title="" alt=""></a>
                <?php }?>
                <?php if(GOOGLE_ID != "" && GOOGLE_SECRET != ""){?>
                <a href="<?=url("oauth/google")?>" title=""><img src="<?=BASE?>assets/images/btn-google.png" title="" alt=""></a>
                <?php }?>
                <?php if(TWITTER_ID != "" && TWITTER_SECRET != ""){?>
                <a href="<?=url("oauth/twitter")?>" title=""><img src="<?=BASE?>assets/images/btn-twitter.png" title="" alt=""></a>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </form>
  </div>
  <div class="copyright"><?=l('shared on codelist.cc')?></div>
</div>
