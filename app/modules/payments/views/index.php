
<div class="pricing-table">
	<?php if(!check_expiration()  && session("admin") != 1){?>
	<div class="container">
		<div class="box-notice-2">
			<i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?=l('Out of date! Please select a package below to continue using')?>
		</div>
	</div>
	<?php }?>
	<div class="title"><?=l('PICK THE BEST PLAN FOR YOU!')?></div>
	<?php if(!empty($package)){?>

	<?php foreach ($package as $row) {
		$price = explode(".", $row->price);
		$permission = json_decode($row->permission);
	?>
	<div class="whole">
		<div class="type <?=($row->default_package == 1)?"bg-recommend":""?>">
			<?php if($row->default_package == 1){?>
			<div class="recommend"></div>
			<?php }?>
			<p><?=$row->name?></p>
			</div>
		<div class="plan">
			<div class="header">
				<span><?=$payment->symbol?></span><?=$price[0]?><sup><?=(isset($price[1])?$price[1]:"00")?></sup>
				<p class="month">/<?=$row->day?> <?=l('days')?></p>
			</div>
			<div class="content">
				<ul>
					<li class="bg-light-green"><?=$permission->maximum_account?> <?=l('Instagram Accounts')?></li>
					<li><?=l('Auto post')?> <?=permission_list($row->permission, 'post')?></li>
					<li><?=l('Auto message')?> <?=permission_list($row->permission, 'message')?></li>
					<li><?=l('Auto activity')?> <?=permission_list($row->permission, 'activity')?></li>
					<li><?=l('Auto search')?> <?=permission_list($row->permission, 'search')?></li>
					<li><?=l('Auto download')?> <?=permission_list($row->permission, 'download')?></li>
				</ul>
			</div>
			<div class="price">
				<?php if(session("uid")){?>
	      			<a href="<?=cn("do_payment?package=".$row->id)?>" class="btn btn-block bg-light-green btn-lg waves-effect"><?=l('UPGRADE NOW')?></a>
				<?php }else{?>
					<a href="javascript:void(0);" data-toggle="modal" data-target="#loginModal" class="btn btn-block bg-light-green btn-lg waves-effect"><?=l('UPGRADE NOW')?></a>
	      		<?php }?>
			</div>
		</div>
	</div>
	<?php }?>

	<?php }?>
</div>
<?=modules::run("blocks/footer")?>