<div class="mryoukins view">
<h2><?php echo __('Mryoukin'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sumflg'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['sumflg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kng'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['kng']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Opday'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['opday']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Capacity'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['capacity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Optime'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['optime']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Anytime Flg'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['anytime_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Period'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['period']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pending Flg'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['pending_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Delflg'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['delflg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Oder'); ?></dt>
		<dd>
			<?php echo h($mryoukin['Mryoukin']['oder']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->Element('left'); ?>