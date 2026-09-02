<h1>サンプル２</h1>
<h2>ユーザ管理</h2>
<h3>ユーザ削除</h3>

<?php echo $this->Form->create('User'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>

<table>

<tr>
<th>ID</th>
<td><?php echo h($this->data['User']['id']); ?></td>
</tr>

<tr>
<th>ユーザ名</th>
<td><?php echo h($this->data['User']['username']); ?></td>
</tr>

</table>

<p>ユーザを削除します。<br />よろしければ「削除」ボタンを押してください</p>

<?php echo $this->Form->end('　削除　'); ?>

<div class="pageLinks">
<p><?php echo $this->Html->link('戻る', array('action' => 'userlist')); ?></p>
</div>
