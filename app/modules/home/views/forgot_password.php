<div class="box-login">
  <div class="login-form">
    <ul class="login-nav" >
        <li class="bg-<?=THEME?>" style="width: 100%; border-radius: 10px 10px 0 0">
            <a href=""><?=l('Forgot password')?></a>
        </li>
    </ul>
    <div class="clearfix"></div>
    <form  action="<?=url('user_management/ajax_forgot_password')?>" data-redirect="<?=url("?st=success")?>">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">email</i>
            </span>
            <div class="form-line">
                <input type="email" class="form-control" name="email" placeholder="<?=l('Email Address')?>" required>
            </div>
        </div>
        <div class="input-group">
            <button type="submitt" class="right btn bg-light-green waves-effect btnActionUpdate"><?=l('Submit')?></button>
        </div>
    </form>
  </div>
  <div class="copyright"><?=l('2016 - 2017 © VTCreators. All rights reserved.')?></div>
</div>