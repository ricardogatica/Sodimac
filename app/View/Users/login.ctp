
	<div class="row">
		<div class="col-lg-4 col-lg-offset-4">
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
			<h2 class="text-center"><?php echo $this->Html->image('logo.jpeg', array('width' => 300, 'alt' => __('Sodimac'))); ?></h2>
			<?php echo $this->Form->input('username', array('placeholder' => __('Usuario'))); ?>
			<?php echo $this->Form->input('password', array('placeholder' => __('Contraseña'))); ?>
			<?php echo $this->Form->input(__('Entrar'), array('type' => 'submit', 'class' => 'btn btn-primary btn-block')); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>

	<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				$('#UserUsername').focus();
			});

		})(jQuery);

	</script>