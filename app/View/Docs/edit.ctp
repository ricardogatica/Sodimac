<h1 class="page-header">
<?php if ($details['Doc']['to_export']): ?>
	<?php echo $this->Html->link(__('Ir a exportar'), array('controller' => 'docs', 'action' => 'export', $details['Doc']['id']), array('class' => 'btn btn-primary pull-right')); ?>
<?php endif; ?>

	<?php echo __('Edición de %s Nº: %s', $details['Type']['name'], $details['Doc']['number'] ? $details['Doc']['number'] : '--'); ?>
	<small><?php echo $details['Doc']['matched'] ? __('Documento conciliado') : __('Documento sin conciliar'); ?></small>
</h1>

<div class="row">
	<div class="col-xs-6 col-md-8 col-lg-7">
		<div class="thumbnail">
			<?php echo $this->Html->image($details['Doc']['preview_normal'], array('class' => 'img-responsive', 'data-zoom-image' => $this->Html->url($details['Doc']['preview_zoom']), 'id' => 'previewDocument')); ?>
		</div>
	</div>
	<div class="col-xs-6 col-md-4 col-lg-5">
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

		<?php echo $this->Form->input('store_id', array('label' => __('Tienda'), 'options' => $stores_users)); ?>
		<?php echo $this->Form->input('type_id', array('label' => __('Tipo Documento'), 'options' => $types)); ?>
		<?php echo $this->Form->input('number', array('type' => 'text', 'label' => __('Nº Documento'))); ?>
		<?php echo $this->Form->input('company', array('type' => 'text', 'label' => __('Razón Social'))); ?>
		<?php echo $this->Form->input('document', array('type' => 'text', 'label' => __('RUT'))); ?>
		<?php echo $this->Form->input('noc', array('type' => 'text', 'label' => __('Nro. OC Cliente'))); ?>
		<?php echo $this->Form->input('ngd', array('type' => 'text', 'label' => __('Nro. Guía de Despacho'))); ?>
		<?php echo $this->Form->input('npvt', array('type' => 'text', 'label' => __('Nº PVT'))); ?>
		<?php echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-primary')); ?>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<?php if ($details['Doc']['dte']): ?>
<h2 class="page-header"><?php echo __('Cedibles conciliados'); ?></h2>

<div class="row">
	<div class="col-xs-9 col-lg-7">
		<?php echo __('* Solo se muestra la primera imagen de los cedibles asociados.'); ?>
	</div>
	<div class="col-xs-3 col-lg-5">
		<?php echo $this->Html->link(__('+ Agregar documento'), array('iframe' => true, 'controller' => 'docs', 'action' => 'search'), array('class' => 'btn btn-block btn-success fancybox fancybox.iframe')); ?>
	</div>
</div>

<br />
<br />

<div class="row">
<?php if (!empty($documents)): ?>
	<?php foreach ($documents AS $row): ?>
	<?php foreach ($row['Doc']['images'] AS $image): ?>
	<div class="col-xs-4 col-lg-2">
		<div class="thumbnail">
			<div class="caption">
				<h5>
					<?php echo $row['Type']['name']; ?><br />
					<small><?php echo __('Nro %s', $row['Doc']['number']); ?></small>
				</h5>
			</div>
			<div style="overflow:hidden;height:200px;">
			<?php echo $this->Html->link($this->Html->image($image['normal'], array('class' => 'img-responsive')), array('iframe' => true, 'controller' => 'docs', 'action' => 'edit', $row['Doc']['id']), array('escape' => false, 'class' => 'fancybox fancybox.iframe')); ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<?php endforeach; ?>
<?php else: ?>
	<div class="col-lg-12">
		<div class="alert alert-warning text-center">
			<?php echo __('No hay documentos asociados a este DTE.'); ?>
		</div>
	</div>
<?php endif; ?>
</div>

<?php endif; ?>