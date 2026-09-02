<div class="mryoukins form">
<?php echo $this->Form->create('Mryoukin'); ?>
<?php $week = array( "日", "月", "火", "水", "木", "金", "土","日夜", "月夜", "火夜", "水夜", "木夜", "金夜", "土夜"  ); ?>

	<fieldset>
		<legend><?php echo __('講　座　内　容　編　集'); ?></legend>
	<?php
//		echo $this->Form->input('id');

//		echo '<table border="0" cellpadding="0" cellspacing="0" width="90%" align="center">';

		echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td  colspan=2>';
		echo $this->Form->input('mlecturer_id', array(
			'label' => '担当講師',
 			'empty' => '固定講師の場合選択',
			));
		echo '</td><td colspan=2>';
		echo $this->Form->input('place_id', array(
			'label' => '規定会場',
			));

		echo '</td><td>';
		echo $this->Form->input('rname', array(
			'label' => '講座名略称',
			));

		echo '</td><td colspan=3>';
		echo $this->Form->input('priname', array(
			'label' => '請求書用講座名',
			));

		echo '</td></tr><tr><td colspan=9>';
		echo $this->Form->input('name', array(
			'label' => '講座名',
			));
		echo '</td></tr><tr><td colspan=9>';
		echo $this->Form->input('overview', array(
			'label' => '講座概要',
			));

		echo '</td></tr><tr><td colspan=9>';
		echo $this->Form->input('prerequisite', array(
			'label' => '受講条件',
			));


		echo '</td></tr><tr><td colspan=9>';
		echo $this->Form->input('remearks', array(
			'label' => '備考',
			));



		echo '</td></tr><tr><td colspan=9>';
		echo $this->Form->input('remearks_sha', array(
			'label' => '会社備考',
			));

		echo '</td></tr><td  colspan=2>';

		echo $this->Form->input('capacity', array(
			'label' => '定　員',
			));
		echo '</td><td colspan=2>';
		echo $this->Form->input('kng', array(
			'label' => '価　格',
			));
		echo '</td><td colspan=2>';
		echo $this->Form->input('kng1', array(
			'label' => '一般講師料',
			));
		echo '</td><td colspan=2>';
				echo $this->Form->input('kng2', array(
			'label' => '付帯講師料',
			));

		echo '</td></tr><tr><td  colspan=9>';
		echo $this->Form->input('opday', array(
			'label' => '開催日(スケジュール開催の場合入れない）',
			));
		echo '</td></tr><tr><td  colspan=7>';
		echo $this->Form->input('optime', array(
			'label' => '規定開催時刻',
			));
		echo '</td><td colspan=2>';

				echo $this->Form->input('daytimes', array(
			'label' => '開催回数',
			));
		echo '</td></tr><tr><td colspan=9>';
				echo $this->Form->input('period', array(
			'type' => 'textarea',
			'label' => '分数×開催回数',
			));
		echo '</td></tr><tr><td >';
		echo $this->Form->input('sumflg', array(
			'label' => '複数講座含む',
			));

		echo '</td><td>';
		echo $this->Form->input('anytime_flg', array(
			'label' => '常時開催'
			));

		echo '</td><td>';
		echo $this->Form->input('pending_flg', array(
			'label' => '開催未定',
			));
		echo '</td><td>';
		echo $this->Form->input('oder', array(
			'label' => '並び順',
			));
		echo '</td><td colspan=2>';
				echo $this->Form->input('delflg', array(
			'label' => '削除の場合チェック',
			));
			echo '</td><td colspan=1>';
			echo $this->Form->input('voice_flg', array(
				'label' => '感想講座名',
				));

		switch ($this->request->data['Mryoukin']['mcoursename_id']) {
			case '1':
			case '11':
			case '12':
			case '13':					// code...

			echo '</td></tr>';

			for($ix = 0; $ix < 14; $ix++) {
				if($ix == 7) echo '</tr><tr>';
				echo '<td>';
				echo $this->Form->input("day" . sprintf("%x", $ix), array(
					'label' =>  $week[$ix] ,
					));
				echo '</td>';
			}

			echo '</td></tr><td>';

			echo '</tr>';
		}
		echo '</table>';
	?>

	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<?php echo $this->Element('left'); ?>
