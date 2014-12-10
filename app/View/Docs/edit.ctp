
<div class="container">
	<div class="row">
		<div class="col-lg-7">
			<?php echo $this->Html->image($details['Doc']['preview_normal'], array('class' => 'img-responsive', 'data-zoom-image' => $this->Html->url($details['Doc']['preview_zoom']), 'id' => 'previewDocument')); ?>
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
<?php if (!empty($cedibles)): ?>
	<div class="row">
	<?php foreach ($cedibles AS $row): ?>
		<div class="col-lg-2">
			<?php echo $this->Html->link($this->Html->image($row['Doc']['preview_normal']), array('iframe' => true, 'controller' => 'docs', 'action' => 'edit', $row['Doc']['id']), array('escape' => false)); ?>
		</div>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
</div>

<?php $this->Html->script('jquery.elevateZoom-3.0.8.min.js', array('inline' => false)); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#previewDocument').on('load', function(){
			$(this).elevateZoom({
				zoomType: "inner",
				cursor: "crosshair"
			});	
		})
	});
</script>