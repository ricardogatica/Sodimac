<h1 class="page-header">
	<?php echo __('Agregar Respaldos'); ?>
	<small><?php echo __('a %s %s', $details['Type']['alias'], $details['Doc']['number']); ?></small>
</h1>

<div class="row">

	<?php
		echo $this->Form->create(
			'Doc',
			array(
				'inputDefaults' => $inputDefaults = array(
					'class' => 'form-control input-lg',
					'div' => array(
						'class' => 'col-xs-4 col-lg-3'
					),
					'wrapInput' => array(
						'class' => 'form-group'
					)
				)
			)
		);
	?>
	<?php echo $this->Form->input('type_id', array('type' => 'select', 'label' => false, 'after' => '<span class="help-block">' . __('Tipo Documento') . '</span>', 'options' => $types, 'empty' => __('Todos'))); ?>
	<?php echo $this->Form->input('number', array('type' => 'text', 'label' => false, 'after' => '<span class="help-block">' . __('Nº Documento') . '</span>', 'placeholder' => __('Todos'))); ?>
	<?php echo $this->Form->submit(__('Buscar'), array('class' => 'btn btn-primary btn-lg', 'div' => $inputDefaults['div'], 'label' => false)); ?>
	<?php echo $this->Form->end(); ?>

</div>

<div class="row">
<?php
	echo $this->Form->create(
		'Push',
		array(
			'inputDefaults' => $inputDefaults = array(
				'class' => 'form-control input-lg',
				'div' => array(
					'class' => 'col-xs-4 col-lg-3'
				),
				'wrapInput' => array(
					'class' => 'form-group'
				)
			)
		)
	);
?>
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th><?php echo __('Tienda'); ?></th>
				<th><?php echo __('Tipo respaldo'); ?></th>
				<th><?php echo __('Nro. respaldo'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($documents AS $row): ?>
			<tr>
				<td><?php echo $this->Form->checkbox('doc.' + $row['Doc']['id'], array('value' => $row['Doc']['id'], 'class' => 'checkbox')); ?></td>
				<td><?php echo $row['Store']['cod']; ?></td>
				<td><?php echo __('%s Nro.', $row['Type']['alias']); ?></td>
				<td><?php echo $this->Html->link($row['Doc']['number'], array('iframe' => true, 'controller' => 'docs', 'action' => 'edit', $row['Doc']['id']), array('class' => 'fancybox fancybox.iframe')); ?></td>
				<td>
					<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', array('controller' => 'docs', 'action' => 'delete', $row['Doc']['id']), array('escape' => false), __('¿Deseas eliminar este documento?')); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->Form->submit(__('Agregar'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
	
</script>