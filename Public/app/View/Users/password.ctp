<div class="box3">
<?php echo $this->Form->create('User'); ?>
<table class="siteInformation table table-bordered">
    <thead>
        <tr>
            <th class="siteInformationField">パスワード設定項目</th>
            <th class="siteInformationDesctiption">入力欄</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th><?php echo $this->Form->label('User.password', 'パスワード　英数字６文字以内'); ?></th>
            <td><?php echo $this->Form->input('password', array('label' => false)); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->Form->label('User.password1', '確認用パスワード'); ?></th>
            <td><?php echo $this->Form->input('password1', array('type' => 'password', 'label' => false)); ?></td>
        </tr>
        <tr>
            <th></th>
            <td>
                <?php echo $this->Form->input('confirmed', array('type' => 'hidden', 'value' => false)); ?>
                <?php echo $this->Form->submit('　設　定　'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php echo $this->Form->end(); ?>
</div>
