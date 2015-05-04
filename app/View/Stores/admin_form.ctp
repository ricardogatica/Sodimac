
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<?php
				echo $this->Form->create(
					'Store',
					array(
						'inputDefaults' => array(
							'div' => 'form-group',
							'label' => false,
							'wrapInput' => 'col-lg-12',
							'class' => 'form-control'
						),
						'class' => 'form-horizontal'
					)
				);
			?>
			<?php echo $this->Form->hidden('id'); ?>
			<?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => __('Nombre de tienda'), 'escape' => false)); ?>
			<?php
				echo $this->Form->input(
					'cod',
					array(
						'placeholder' => __('Código de tienda'),
						'readonly' => true
					)
				); 
			?>

			<div class="form-group">
				<div class="col-lg-6">
					<button class="btn btn-primary btn-block">
						<?php echo __('Guardar'); ?>
					</button>
				</div>
				<div class="col-lg">
					<?php echo $this->Html->link(__('Cancelar'), array('admin' => true, 'controller' => 'stores', 'action' => 'index'), array('class' => 'btn btn-link btn-block')); ?>
				</div>
			</div>
			
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
