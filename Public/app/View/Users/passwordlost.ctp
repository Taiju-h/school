<?php echo $this->Html->css('heartfull');
 echo $this->Element('top'); ?>
<div class="box3">

<?php echo $this->Element('top_toi'); ?>

<?php echo $this->Form->create('User') ?>
	<fieldset>
	</br>
登録されたメールアドレスを入力してください。</br>
</br>
	<?php
		
		echo $this->Form->input('usrmail', array(
			'label' => 'メールアドレス',
			'error' => '必須入力項目です。'
			));
	?>
		<strong><span style="color:red;">※届かない場合は受信制限をかけている場合がございますのでご確認ください。</br>
	<a href="http://school.heartf.com/index.php?go=UuqY2M" title="解除設定">	設定の詳細はこちらをクリックしてください。</a></span></strong>
	</fieldset>
<?php echo $this->Form->end(__('登　録')); ?>
</div>
