<h3>講座種類を選んでください。</h3>
<center>
別ウィンドウで開きます。</br>
<?php 
echo $this->Html->image('kouza_room.png', array('alt'=>'通学コース'));
echo '<div class="kouza_1"> ';
echo $this->Html->link('', array('action' => 'selectsub', 1), array('target'=> '_blank'));
echo "</div>";
echo '<div class="kouza_2"> ';
echo $this->Html->link('         ', array('action' => 'selectsub', 2), array('target'=> '_blank'));
echo "</div>";
echo '<div class="kouza_3"> ';
echo $this->Html->link('', array('action' => 'selectsub'), array('target'=> '_blank'));
echo "</div>";
 
//echo $this->Html->image('kouza_net.png', array('alt'=>'通信コース'));
?>
別ウィンドウで開きます。</br>

</center>