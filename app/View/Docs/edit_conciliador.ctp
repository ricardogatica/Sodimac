<h1 class="page-header">
	<?php if ($details['Doc']['to_export']): ?>
		<?php echo $this->Html->link(__('Ir a exportar'), array('controller' => 'docs', 'action' => 'pdf_export', $details['Doc']['id']), array('class' => 'btn btn-primary pull-right')); ?>
	<?php endif; ?>
	<?php echo __('Edición de %s Nº: %s', $details['Type']['name'], $details['Doc']['number'] ? $details['Doc']['number'] : '--'); ?>
	<small><?php echo $details['Doc']['matched'] ? __('Documento conciliado') : __('Documento sin conciliar'); ?></small>
</h1>

<div role="tabpanel">
	<div class="row">
		<div class="col-xs-6 col-md-6 col-lg-6">
			<ul class="nav nav-tabs">

				<li role="presentation" class="active">
					<?php echo $this->Html->link(sprintf('%s: %s', $details['Type']['alias'], $details['Doc']['number']), '#' . $details['Type']['alias'] . $details['Doc']['number']); ?>
				</li>

			</ul>
		</div>

		<div class="col-xs-6 col-md-6 col-lg-6">
			<ul class="nav nav-tabs" role="tablist">
			<?php foreach ($documents AS $key => $doc): ?>
				<li role="presentation" class="<?php echo !$key ? 'active' : ''; ?>">
					<?php echo $this->Html->link(sprintf('%s: %s', $doc['Type']['alias'], $doc['Doc']['number']), '#' . $doc['Type']['alias'] . $doc['Doc']['number'], array('aria-controls' => 'settings', 'role' => 'tab', 'data-toggle' => 'tab')); ?>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6 col-md-6 col-lg-6">
			
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6 col-md-6 col-lg-6">
			<nav style="height:80px;">
			</nav>

			<?php
				if (!empty($details['Doc']['images'])):
					$image = current($details['Doc']['images']);
			?>

				<div class="thumbnail" style="overflow:hidden;height:500px;">
				<?php echo $this->Html->link($this->Html->image($image['normal'], array('class' => 'img-responsive', 'data-zoom-image' => $this->Html->url($image['zoom']))), $image['normal'], array('escape' => false, 'target' => '_blank')); ?>
				</div>

			<?php endif; ?>

			<div class="clearfix"></div>
			
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
				<?php echo $this->Form->hidden('Doc.' . $details['Doc']['id'] . '.id'); ?>
				<?php echo $this->Form->hidden('Doc.' . $details['Doc']['id'] . '.matched'); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.type_id', array('label' => __('Tipo Documento'), 'options' => $types)); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.number', array('type' => 'text', 'label' => __('Nº Documento'))); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.company', array('type' => 'text', 'label' => __('Razón Social'))); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.document', array('type' => 'text', 'label' => __('RUT'))); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.noc', array('type' => 'text', 'label' => __('Nro. OC Cliente'))); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.ngd', array('type' => 'text', 'label' => __('Nro. Guía de Despacho'))); ?>
				<?php echo $this->Form->input('Doc.' . $details['Doc']['id'] . '.npvt', array('type' => 'text', 'label' => __('Nº PVT'))); ?>
				<?php echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-primary')); ?>
			<?php echo $this->Form->end(); ?>
		</div>

		<div class="col-xs-6 col-md-6 col-lg-6">
			
			<div class="tab-content">

				<?php foreach ($documents AS $key => $doc): ?>
				<div role="tabpanel" class="tab-pane <?php echo !$key ? 'active' : ''; ?>" id="<?php echo $doc['Type']['alias'] . $doc['Doc']['number']; ?>">

					<nav style="height:80px;">
						<ul class="pagination">
						<?php foreach ($doc['Doc']['images'] AS $key => $image): ?>
							<li><a href="#doc<?php echo $doc['Doc']['id'] . $image['id']; ?>"><?php echo $key + 1; ?></a></li>
						<?php endforeach; ?>
						</ul>
					</nav>
					<div class="slides" style="height:500px;">
						<?php if (empty($doc['Doc']['images'])): ?>
							<h1><?php echo __('Sin imagen'); ?></h1>
						<?php endif; ?>

						<?php foreach ($doc['Doc']['images'] AS $key => $image): ?>
						<div class="slide thumbnail" id="doc<?php echo $doc['Doc']['id'] . $image['id']; ?>" style="overflow:hidden;height:500px;">
							<?php echo $this->Html->link($this->Html->image($image['normal'], array('class' => 'img-responsive', 'data-zoom-image' => $this->Html->url($image['zoom']))), $image['normal'], array('escape' => false, 'target' => '_blank')); ?>
						</div>
						<?php endforeach; ?>
					</div>

					<div class="clearfix"></div>

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
						<?php echo $this->Form->hidden('Doc.' . $doc['Doc']['id'] . '.id'); ?>
						<?php echo $this->Form->hidden('Doc.' . $doc['Doc']['id'] . '.matched'); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.type_id', array('label' => __('Tipo Documento'), 'options' => $types)); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.number', array('type' => 'text', 'label' => __('Nº Documento'))); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.company', array('type' => 'text', 'label' => __('Razón Social'))); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.document', array('type' => 'text', 'label' => __('RUT'))); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.noc', array('type' => 'text', 'label' => __('Nro. OC Cliente'))); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.ngd', array('type' => 'text', 'label' => __('Nro. Guía de Despacho'))); ?>
						<?php echo $this->Form->input('Doc.' . $doc['Doc']['id'] . '.npvt', array('type' => 'text', 'label' => __('Nº PVT'))); ?>
						<?php echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-primary')); ?>
					<?php echo $this->Form->end(); ?>

				</div>
				<?php endforeach; ?>

			</div>
		</div>
	</div>
</div>

<?php if ($details['Doc']['dte']): ?>

<div class="row">
	<div class="col-lg-12">
		<?php echo $this->Html->link(__('+ Agregar respaldo'), array('iframe' => true, 'controller' => 'docs', 'action' => 'add', $details['Doc']['id']), array('class' => 'btn btn-block btn-success fancybox fancybox.iframe')); ?>
	</div>
</div>

<?php endif; ?>