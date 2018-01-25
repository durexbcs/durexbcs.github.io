<div class="pricing-table">
	<div class="title uc"><?=l('Select a payment services')?></div>
	<div class="whole">
		<div class="plan">
			<div class="header">
				<img src="<?=BASE."assets/images/paypal_logo.png"?>" height="100">
			</div>
			<div class="price">
				<?php if(session("uid")){?>
	      			<a href="<?=cn("do_payment?package=".get("package"))?>" class="btn btn-block bg-light-green btn-lg waves-effect"><?=l('PAYMENT NOW')?></a>
				<?php }else{?>
					<a href="javascript:void(0);" data-toggle="modal" data-target="#loginModal" class="btn btn-block bg-light-green btn-lg waves-effect"><?=l('PAYMENT NOW')?></a>
	      		<?php }?>
			</div>
		</div>
	</div>
</div>
<?=modules::run("blocks/footer")?>
