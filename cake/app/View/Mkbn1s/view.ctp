<div class="mkbn1s view">
<h2><?php echo __('Mkbn1'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mkbn1['Mkbn1']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($mkbn1['Mkbn1']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dname'); ?></dt>
		<dd>
			<?php echo h($mkbn1['Mkbn1']['dname']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->Element('left'); ?>