<script type="text/javascript"><!--
function doToggleClassName(obj, onClassName, offClassName){obj.className = (obj.className != onClassName) ? onClassName : offClassName;}
function getParentObj(obj){return obj.parentElement || obj.parentNode;}
//--></script>

<style type="text/css">
.vmenuitem{background-color:#ffffcc; cursor:pointer; padding:5px; width:80%;}
.vmenu_on, .vmenu_off{margin:2px 0px;}
.vmenu_on .vmenuitem{border:1px solid #cccc77; border-left:20px solid #cccc77;}
.vmenu_off .vmenuitem{border:1px solid #77cccc; border-left:20px solid #77cccc;}
.vmenu_on ul{display:auto; margin:2px auto;}
.vmenu_off ul{display:none;}
</style>
<div class="actions">
	<h3><?php echo __('Actions');?></h3>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">スケジュール</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('スケジュール一覧'), array('controller' => 'eschedules', 'action' => 'add')); ?> </li>
	        	<li><?php echo $this->Html->link(__('感想一覧'), array('controller' => 'Evoices', 'action' => 'add')); ?> </li>

 	 </ul>
	</div>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">コンテンツファイル</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('コンテンツ一覧'), array('controller' => 'mfiles', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('コンテンツ追加'), array('controller' => 'mfiles', 'action' => 'add')); ?> </li>
				<li><?php echo $this->Html->link(__('コンテンツ関連付一覧'), array('controller' => 'massociations', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('コンテンツ関連付作成'), array('controller' => 'massociations', 'action' => 'add')); ?> </li>

 	 </ul>
	</div>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">区分コンテンツ</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('大区分　追加／一覧'), array('controller' => 'mkbn1s', 'action' => 'add')); ?> </li>
	        	<li><?php echo $this->Html->link(__('中区分　追加／一覧'), array('controller' => 'mkbn2s', 'action' => 'add')); ?> </li>
	        	<li><?php echo $this->Html->link(__('小区分　追加／一覧'), array('controller' => 'mkbn3s', 'action' => 'add')); ?> </li>

  </ul>
	</div>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">申込関係</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('集客講座申込'), array('controller' => 'Msubscriptions', 'action' => 'add_doku')); ?> </li>
	        	<li><?php echo $this->Html->link(__('申込詳細'), array('controller' => 'Msubscriptions', 'action' => 'index')); ?> </li>
	        	<li><?php echo $this->Html->link(__('入金確認（未送信）'), array('controller' => 'Vssubscriptions', 'action' => 'index')); ?> </li>
	        	<li><?php echo $this->Html->link(__('生徒一覧'), array('controller' => 'Musers', 'action' => 'index')); ?> </li>
 	        	<li><?php echo $this->Html->link(__('参加申し込み一覧'), array('controller' => 'eschedules', 'action' => 'sankalist')); ?> </li>
 	        	<li><?php echo $this->Html->link(__('日付別参加者一覧'), array('controller' => 'eschedules', 'action' => 'todaylist')); ?> </li>
 	        	<li><?php echo $this->Html->link(__('講師　請求書'), array('controller' => 'eschedules', 'action' => 'seikyusho')); ?> </li>
  	        	<li><?php echo $this->Html->link(__('受講済み(分割払い含む)'), array('controller' => 'Msubscriptions', 'action' => 'index', 50)); ?> </li>
  	        	<li><?php echo $this->Html->link(__('紹介インセンティブ対象'), array('controller' => 'Msubscriptions', 'action' => 'index', 999)); ?> </li>
	        	<li><?php echo $this->Html->link(__('入学日経過未受講一覧'), array('controller' => 'Msubscriptions', 'action' => 'index', 30)); ?> </li>

 </ul>
	</div>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">通信講座</div>
	  <ul>
 	        	<li><?php echo $this->Html->link(__('未発送(含む特典未発送)'), array('controller' => 'Msubscriptions', 'action' => 'index', 888)); ?> </li>
 	  <ul>
	</div>
	
	
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">入学及び受講（社長担当分）</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('入学及び受講'), array('controller' => 'Msubscriptions', 'action' => 'index', 40)); ?> </li>
	        	<li><?php echo $this->Html->link(__('分割支払い'), array('controller' => 'Msubscriptions', 'action' => 'index', 55)); ?> </li>
  </ul>
	</div>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">講座出欠</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('参加チェック'), array('controller' => 'todays', 'action' => 'index')); ?> </li>
 	 </ul>
	</div>


<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">講座・講座関連付け</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('講座一覧'), array('controller' => 'Mryoukins', 'action' => 'index')); ?> </li>
	        	<li><?php echo $this->Html->link(__('講座関連付け(占技)'), array('controller' => 'Msums', 'action' => 'index')); ?> </li>
	        	<li><?php echo $this->Html->link(__('講座関連付け(独立講座付帯)'), array('controller' => 'Msum2s', 'action' => 'index')); ?> </li>
 	 </ul>
</div>
	<div class="vmenu_on">
	  <div class="vmenuitem" onclick="doToggleClassName(getParentObj(this),'vmenu_on','vmenu_off')">アクセスログ</div>
	  <ul>
	        	<li><?php echo $this->Html->link(__('アクセスログ'), array('controller' => 'Efilelogs', 'action' => 'index')); ?> </li>
  </ul>
	</div>

</div>
