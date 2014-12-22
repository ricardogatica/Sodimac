<h1 class="page-header">
	<?php if ($details['Doc']['to_export']): ?>
		<?php echo $this->Html->link(__('Ir a exportar'), array('controller' => 'docs', 'action' => 'pdf_export', $details['Doc']['id']), array('class' => 'btn btn-primary pull-right')); ?>
	<?php endif; ?>
	<?php echo __('Edición de %s Nº: %s', $details['Type']['name'], $details['Doc']['number'] ? $details['Doc']['number'] : '--'); ?>
	<small><?php echo $details['Doc']['matched'] ? __('Documento conciliado') : __('Documento sin conciliar'); ?></small>
</h1>

<div class="row">
<?php foreach ($images AS $image): ?>
	<div class="col-xs-3 col-md-3 col-lg-3">
		<div class="thumbnail">
			<?php echo $this->Html->image($image['normal'], array('class' => 'img-responsive preview', 'data-zoom-image' => $this->Html->url($image['zoom']))); ?>
		</div>
	</div>
<?php endforeach; ?>
</div>

<?php

	

	echo $this->Html->link(__('+ Agregar respaldo'), array('iframe' => true, 'controller' => 'docs', 'action' => 'add', $details['Doc']['id']), array('class' => 'btn btn-block btn-success fancybox fancybox.iframe'));

?>

<div class="row">
<?php foreach ($docs AS $row): ?>
	<div class="col-xs-3 col-md-3 col-lg-3">
		<?php
			echo $this->Form->create(
				'Doc',
				array(
					'inputDefaults' => array(
						'class' => 'form-control input-lg',
						'div' => array(
							'class' => 'form-group'
						),
						'wrapInput' => false
					)
				)
			);
		?>

		<?php echo $this->Form->hidden('Doc.' . $row['Doc']['id'] . '.id'); ?>
		<?php echo $this->Form->hidden('Doc.' . $row['Doc']['id'] . '.matched'); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.store_id', array('label' => __('Tienda'), 'options' => $stores_users)); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.type_id', array('label' => __('Tipo Documento'), 'options' => $types)); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.number', array('type' => 'text', 'label' => __('Nº Documento'))); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.company', array('type' => 'text', 'label' => __('Razón Social'))); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.document', array('type' => 'text', 'label' => __('RUT'))); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.noc', array('type' => 'text', 'label' => __('Nro. OC Cliente'))); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.ngd', array('type' => 'text', 'label' => __('Nro. Guía de Despacho'))); ?>
		<?php echo $this->Form->input('Doc.' . $row['Doc']['id'] . '.npvt', array('type' => 'text', 'label' => __('Nº PVT'))); ?>
		<?php echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-primary')); ?>
		<?php echo $this->Form->end(); ?>
	</div>
<?php endforeach; ?>
</div>
