
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<?php
				echo $this->Form->create(
					'User',
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
			<?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => __('Nombres y Apellidos'))); ?>
			<?php
				echo $this->Form->input(
					'username',
					array(
						'type' => 'text',
						'placeholder' => __('Usuario'),
						'readonly' => (!empty($this->data['User']['id']) && !in_array(AuthComponent::user('profile'), array('developer', 'admin'))
						)
					)
				);
			?>
			<?php
				echo $this->Form->input(
					'password',
					array(
						'type' => 'password',
						'placeholder' => (!empty($this->data['User']['id']) ? __('Actualizar contraseña') : __('Contraseña'))
					)
				); 
			?>
			<?php echo $this->Form->input('profile', array('type' => 'select', 'empty' => array(__('Perfil dentro del sistema')), 'options' => $profiles, 'escape' => false)); ?>

			<?php echo $this->Form->input('StoreUser.store_id', array('type' => 'select', 'multiple' => 'multiple', 'options' => $stores, 'class' => 'form-control select')); ?>

			<div class="form-group">
				<div class="col-lg-6">
					<button class="btn btn-primary btn-block">
						<?php echo __('Guardar'); ?>
					</button>
				</div>
				<div class="col-lg">
					<?php echo $this->Html->link(__('Cancelar'), array('admin' => true, 'controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-link btn-block')); ?>
				</div>
			</div>
			
			<?php echo $this->Form->end(); ?>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			$('.select').select2();
		});
	</script>