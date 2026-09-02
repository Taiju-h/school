<div class="msubscriptions form">
<?php echo $this->Form->create('Msubscription'); ?>
	<fieldset>
		<legend><?php echo __('支払情報変更'); ?></legend>
	<?php
		echo '<table><tr><td>';
		echo $this->Form->label('Msubscription', 'ユーザ名 :' . $this->request->data["Muser"]['name']);
		echo '</td><td colspan = "2">';
		echo $this->Form->label('Msubscription', '講 座 名 :' . $this->request->data["Mryoukin"]['name']);
		echo '</td></tr>';
		echo '<tr><td>';
		echo $this->Form->input('mday_id', array(
			'label' => '曜日',
			));
		echo '</td><td>';
		echo $this->Form->input('mworkst_id', array(
			'label' => 'ステータス',
			'error' => '必須入力項目です。'
			));
		echo '</td><td>';
		echo $this->Form->input('mpaymentmethod_id', array(
			'label' => '支払い方法',
			'error' => '必須入力項目です。'
			));
		echo '</td></tr>';
		echo '<tr><td>';
		echo $this->Form->label('Msubscription', '入　学　日');
			$name = "admissiondate";
			echo $this->Form->year($name, date('Y') + 1, date('Y') - 1);
			echo $this->Form->label('Msubscription', '年');
			echo $this->Form->month($name, array('monthNames' => false));
			echo $this->Form->label('Msubscription', '月');
			echo $this->Form->day($name, array('dayhNames' => false));
			echo $this->Form->label('Msubscription', '日') . '</br>';
		echo '</td><td>';
		echo $this->Form->input('mintroduction_id', array(
			'label' => 'インセンティブ【DB直接入力？】',
			'error' => '必須入力項目です。'
			));
		echo '</td><td>';
		echo $this->Form->input('sale_id', array(
			'label' => '紹介【DB直接入力？】',
			'error' => '必須入力項目です。'
			));
		echo '</td></tr></table><td>';

		
		echo $this->Form->label('Msubscription', '備　　　考');
		echo $this->Form->textarea('remarks', array(
			'cols'=>50, 'rows'=>3));
		echo $this->Form->label('Msubscription', 'ハートフル備考');
		echo $this->Form->textarea('remarks_sha', array(
			'cols'=>50, 'rows'=>8));
		echo '<table><tr><td>';
		echo $this->Form->input('fee', array(
			'label' => '料金',
			'error' => '必須入力項目です。'
			));
		echo '</td><td>';
		echo $this->Form->input('paidkng', array(
			'label' => '残金',
			'error' => '必須入力項目です。'
			));
		echo '</td></tr></table>';
		?>

		<h3>通信講座ステータス</h3>
		<table>
			<tr><td>
			<?php
		echo $this->Form->label('Msubscription', '希望日');

			$name = "arrival_date";
			echo $this->Form->year($name, date('Y') + 1 , date('Y') - 1);
			echo $this->Form->label('Msubscription', '年');
			echo $this->Form->month($name, array('monthNames' => false));
			echo $this->Form->label('Msubscription', '月');
			echo $this->Form->day($name, array('dayhNames' => false));
			echo $this->Form->label('Msubscription', '日') ?>
			</td>			<td>

			<?php echo $this->Form->input('arrival_time', array(
			'label' => '時間指定',
			)); ?>
			</td><td>

		<?php echo $this->Form->label('Msubscription', '発送日');

			$name = "shipment_date";
			echo $this->Form->year($name, date('Y') , date('Y') - 2);
			echo $this->Form->label('Msubscription', '年');
			echo $this->Form->month($name, array('monthNames' => false));
			echo $this->Form->label('Msubscription', '月');
			echo $this->Form->day($name, array('dayhNames' => false));
			echo $this->Form->label('Msubscription', '日') ?>
			</td>			<td>
			<?php echo $this->Form->input('invoice_no', array(
			'label' => '伝票番号',
			)); ?></td>			<td>
		<tr><td colspan = 4>
		<?php echo $this->Form->label('Msubscription', '認定書発送日');

			$name = "certification_date";
			echo $this->Form->year($name, date('Y') , date('Y') - 2);
			echo $this->Form->label('Msubscription', '年');
			echo $this->Form->month($name, array('monthNames' => false));
			echo $this->Form->label('Msubscription', '月');
			echo $this->Form->day($name, array('dayhNames' => false));
			echo $this->Form->label('Msubscription', '日') ?>

		</td></tr>

		</table>

		<h3>受講日ステータス</h3>
		<table>
			<tr><td>1回目</td><td>2回目</td><td>3回目</td></tr>
			<tr><td>
			<?php echo $this->Form->input('mstudentst1_id', array(
			'label' => '受講日ステータス',
			)); ?></td>			<td>

			<?php echo $this->Form->input('mstudentst2_id', array(
			'label' => '受講日ステータス',
			)); ?></td>			<td>

			<?php echo $this->Form->input('mstudentst3_id', array(
			'label' => '受講日ステータス',
			)); ?></td></tr></table>

		<h3>支払テーブル</h3>
		<table>
			<tr><td>回</td><td>日付</td><td>金額【税抜】</td></tr>

		<?php
		if($this->request->data["Msubscription"]['mpaymentmethod_id'] == 5)
			$ixmax = 8;
		else $ixmax = 1;
		$ix = 1;
		for($ix = 1; $ix <= $ixmax; $ix++) {

			echo "<tr><td>" .$ix . '回目</td>';
			$name = "date". $ix;
			echo "<td>";
			echo $this->Form->year($name, date('Y') , date('Y') - 2);
			echo $this->Form->label('Msubscription', '年');
			echo $this->Form->month($name, array('monthNames' => false));
			echo $this->Form->label('Msubscription', '月');
			echo $this->Form->day($name, array('dayhNames' => false));
			echo $this->Form->label('Msubscription', '日');
			echo '</td>';
			echo "<td>" .   $this->Form->text('kng' . $ix). '</td></tr>';

		}
	echo "</table>";
	?>
	</fieldset>
<?php echo $this->Form->end(__('編集')); ?>
</div>
<?php echo $this->Element('left'); ?>
