<div class="massociations view">
<h2><?php echo __('Massociation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($massociation['Massociation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mfile'); ?></dt>
		<dd>
			<?php echo $this->Html->link($massociation['Mfile']['title'], array('controller' => 'mfiles', 'action' => 'view', $massociation['Mfile']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mkbn1'); ?></dt>
		<dd>
			<?php echo $this->Html->link($massociation['Mkbn1']['name'], array('controller' => 'mkbn1s', 'action' => 'view', $massociation['Mkbn1']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mkbn2'); ?></dt>
		<dd>
			<?php echo $this->Html->link($massociation['Mkbn2']['name'], array('controller' => 'mkbn2s', 'action' => 'view', $massociation['Mkbn2']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mkbn3'); ?></dt>
		<dd>
			<?php echo $this->Html->link($massociation['Mkbn3']['name'], array('controller' => 'mkbn3s', 'action' => 'view', $massociation['Mkbn3']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($massociation['Massociation']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($massociation['Massociation']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updateid'); ?></dt>
		<dd>
			<?php echo h($massociation['Massociation']['updateid']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->Element('left'); ?>