<?php //echo $this->Html->css('heartfmob');
	if( $this->Session->read('user.cardflg')) { ?> <form action="https://secure.telecomcredit.co.jp/inetcredit/secure/one-click-order.pl" method="post" target="_blank">
	<?php } else { ?> <form action="https://secure.telecomcredit.co.jp/inetcredit/secure/order.pl" method="post"  target="_blank">
	<?php }
	$kng = 0; ?>
<div align="center">
	<table border="0" cellspacing="1" class="contact_form m10">
	<tr>
			<th>講　座　名</th>
			<th>料　金</th>
	</tr>
	<?php foreach ($Msubscriptions as $Msubscription): ?>
	<tr>
		<td width = "70%" class="c_right"><?php echo h($Msubscription['Mryoukin']['name']); ?>&nbsp;</td>
		<td align="right" class="c_left"><?php echo h(number_format($Msubscription['Mryoukin']['kng'] * TAX)); ?>&nbsp;</td>
	</tr>
 <?php endforeach; ?>
	<?php $kng1 =  (int)($Msubscription['Mryoukin']['kng'] * TAX); ?>

		<td width = "70%" class="c_right">合　　計</td>
		<td align="right" class="c_left"><?php echo h(number_format($kng1)); ?>&nbsp;</td>
	</tr>

	</table>
		<input type="hidden" name="clientip" value="72531">
		<input type="hidden" name="money" value="<?php $wk =$kng1;  echo $wk;?>">
		<input type="hidden" name="usrtel" value="<?php echo  $this->Session->read('user.usrtel')?>">
		<input type="hidden" name="usrmail" value="<?php echo $this->Session->read('user.usrmail')?>">
		<input type="hidden" name="sendid" value="<?php printf("%s%09d", "SHCOOL",  $this->Session->read('user.id'));?>">
		<input type="hidden" name="redirect_url" value="<?php echo  FULL_BASE_URL . "/index.php?go=qTHNBu"?>">
<p>　</p>
<?php echo $this->Form->submit(__('card_b.gif')); ?>
</div>
