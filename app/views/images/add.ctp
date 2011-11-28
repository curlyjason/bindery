<div class="images form">
<?php echo $this->Form->create('Image');?>
	<fieldset>
 		<legend><?php __('Add Image'); ?></legend>
	<?php
		echo $this->Form->input('img_file',array('type' => 'file'));
		echo $this->Form->input('alt');
		echo $this->Form->input('picture_datetime');
		echo $this->Form->input('mimetype');
		echo $this->Form->input('filesize');
		echo $this->Form->input('width');
		echo $this->Form->input('height');
                echo $this->Form->input('gallery', array(
                    'type'=>'radio', 'value'=>'dispatches', 'options'=> array(
                        'dispatches'=>'Dispatch', 'exhibits'=>'Exhibit' 
                    )
                ));
		echo $this->Form->input('created');
		echo $this->Form->input('modified');
	?>
	</fieldset>
 
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Images', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Dispatches', true), array('controller' => 'dispatches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dispatch', true), array('controller' => 'dispatches', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Exhibits', true), array('controller' => 'exhibits', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Exhibit', true), array('controller' => 'exhibits', 'action' => 'add')); ?> </li>
	</ul>
</div>