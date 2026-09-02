<div class="mfiles view">
<h2><?php echo __('Mfile'); ?></h2>
	<dl>
		<dt><?php echo __('タイトル'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ファイルサイズ'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['filesize']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ファイルタイプ'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['filetype']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('サムネール'); ?></dt>
		<dd>
			<?php $image = base64_encode($mfile['Mfile']['thumbnail']);
			echo "<img src='data:image/jpeg;base64,${image}' />"; ?>
		</dd>
		<dt><?php echo __('説　明'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('会員限定'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['limit_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('開示制限'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['disp_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('関連付け一覧に表示するか？'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['list_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('禁断の書'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['taboo_flg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('作成日'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('更新日'); ?></dt>
		<dd>
			<?php echo h($mfile['Mfile']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->Element('left'); ?>