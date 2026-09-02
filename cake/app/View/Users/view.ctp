<h1>サンプル２</h1>
<h2>ユーザ管理</h2>
<h3>ユーザ詳細</h3>

<table>

<tr>
<th>ID</th>
<td><?php echo h($userData['User']['id']); ?></td>
</tr>

<tr>
<th>ユーザ名</th>
<td><?php echo h($userData['User']['username']); ?></td>
</tr>

</table>

<div class="pageLinks">
<p><?php echo $this->Html->link('戻る', array('action' => 'userlist')); ?></p>
</div>
