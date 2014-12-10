
<div class="container">
	<div class="row">
		<div class="col-lg-7">
			<?php foreach ($details['Doc']['images'] AS $image): ?>
				<?php echo $this->Html->image($image, array('class' => 'img-responsive')); ?>
			<?php endforeach; ?>
		</div>
		<div class="col-lg-5">
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

			<?php echo $this->Form->input('type_id', array('label' => __('Tipo Documento'), 'options' => $types)); ?>
			<?php echo $this->Form->input('number', array('label' => __('Nº Documento'))); ?>
			<?php echo $this->Form->input('company', array('label' => __('Razón Social'))); ?>
			<?php echo $this->Form->input('document', array('label' => __('RUT'))); ?>
			<?php echo $this->Form->input('noc', array('label' => __('Nro. OC Cliente'))); ?>
			<?php echo $this->Form->input('ngd', array('label' => __('Nro. Guía de Despacho'))); ?>
			<?php echo $this->Form->input('npvt', array('label' => __('Nº PVT'))); ?>

			<?php echo $this->Form->end(); ?>
		</div>
	</div>
	<div class="row">

	</div>
</div>