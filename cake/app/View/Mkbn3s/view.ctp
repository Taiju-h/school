<div class="mkbn3s view">
<h2><?php echo __('Mkbn3'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mkbn3['Mkbn3']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($mkbn3['Mkbn3']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dname'); ?></dt>
		<dd>
			<?php echo h($mkbn3['Mkbn3']['dname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Oder'); ?></dt>
		<dd>
			<?php echo h($mkbn3['Mkbn3']['oder']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->Element('left'); ?>