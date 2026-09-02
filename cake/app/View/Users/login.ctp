<?php $this->Html->addCrumb( '管理画面', '/'); ?>
<?php $this->Html->addCrumb( 'ログイン'); ?>
<?php $this->set('breadcrumb_for_layout', $this->Html->getCrumbs(' &raquo; ')) ?>
<?php $this->set('title_for_layout', 'ログイン'); ?>

<?php echo $this->Form->create('User'); ?>
<table class="siteInformation table table-bordered">
    <thead>
        <tr>
            <th class="siteInformationField">ログイン項目</th>
            <th class="siteInformationDesctiption">入力欄</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th><?php echo $this->Form->label('User.username', 'ユーザ名'); ?></th>
            <td><?php echo $this->Form->input('username', array('label' => false, 'style' => 'width:200px'
)); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->Form->label('User.password', 'パスワード'); ?></th>
            <td><?php echo $this->Form->input('password', array('label' => false, 'style' => 'width:200px')); ?></td>
        </tr>
        <tr>
            <th></th>
            <td>
                <?php echo $this->Form->input('confirmed', array('type' => 'hidden', 'value' => false)); ?>
                <?php echo $this->Form->submit('　ログイン　'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php echo $this->Form->end(); ?>
