<div class="images index">
	<h2><?php __('Images');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('img_file');?></th>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('picture_datetime');?></th>
			<th><?php echo $this->Paginator->sort('gallery');?></th>
			<th><?php echo $this->Paginator->sort('alt');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('mimetype');?></th>
			<th><?php echo $this->Paginator->sort('filesize');?></th>
			<th><?php echo $this->Paginator->sort('width');?></th>
			<th><?php echo $this->Paginator->sort('height');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($images as $image):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $image['Image']['img_file']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['id']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['picture_datetime']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['gallery']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['alt']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['modified']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['created']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['mimetype']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['filesize']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['width']; ?>&nbsp;</td>
		<td><?php echo $image['Image']['height']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $image['Image']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $image['Image']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $image['Image']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $image['Image']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Image', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Dispatches', true), array('controller' => 'dispatches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dispatch', true), array('controller' => 'dispatches', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Exhibits', true), array('controller' => 'exhibits', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Exhibit', true), array('controller' => 'exhibits', 'action' => 'add')); ?> </li>
	</ul>
</div>