<div class="mfiles view">
<h2><?php echo __('Mfile'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mkbn1'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mfile['Mkbn1']['name'], array('controller' => 'mkbn1s', 'action' => 'view', $mfile['Mkbn1']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mkbn2'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mfile['Mkbn2']['name'], array('controller' => 'mkbn2s', 'action' => 'view', $mfile['Mkbn2']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Thumbnail'); ?></dt>
		<dd>
			<?php $image = base64_encode($mfile['Mfile']['thumbnail']);
			echo "<img src='data:image/jpeg;base64,${image}' />";?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Limit Flg'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['limit_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Disp Flg'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['disp_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('一覧に戻る。'), array('action' => 'index')); ?> </li>
	</ul>
</div>
