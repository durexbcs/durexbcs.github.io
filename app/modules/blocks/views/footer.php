<div class="footer">
    <div class="container wide">
        <div class="row">
            <div class="col-md-9 coptright"><?=l('2016 - 2017 © VTCreators. All rights reserved.')?></div>
            <div class="col-md-3 text-right social">
            <?php if(FACEBOOK_PAGE || TWITTER_PAGE || PINTEREST_PAGE != "" || INSTAGRAM_PAGE != ""){?>
              <a href="<?=FACEBOOK_PAGE?>">
                <img src="<?=BASE?>assets/images/facebook.png" alt="Share this with Facebook" class="social-icons">
              </a>
              <a href="<?=TWITTER_PAGE?>">
                <img src="<?=BASE?>assets/images/twitter.png" alt="Share this with Twitter" class="social-icons"> 
              </a>
              <a href="<?=PINTEREST_PAGE?>">
                <img src="<?=BASE?>assets/images/pinterest.png" alt="Share this with Pinterest" class="social-icons">
              </a>
              <a href="<?=INSTAGRAM_PAGE?>">
                <img src="<?=BASE?>assets/images/instagram.png" alt="Share this with Instagram" class="social-icons">
              </a>
            <?php }?>
            </div>
        </div>
    </div>
</div>