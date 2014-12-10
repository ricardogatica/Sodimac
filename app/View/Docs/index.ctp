
	<br />

	<div class="btn-group btn-group-justified" role="group">
		<?php echo $this->Html->link(__('Documentos conciliados'), array('controller' => 'docs', 'action' => 'index', 'match'), array('class' => 'btn btn-default ' . ($type == 'match' ? 'active' : ''))); ?>
		<?php echo $this->Html->link(__('Documentos por conciliar'), array('controller' => 'docs', 'action' => 'index', 'dte'), array('class' => 'btn btn-default ' . ($type == 'dte' ? 'active' : ''))); ?>
	</div>

	<br />

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="collapse navbar-collapse">
				<?php
					echo $this->Form->create(
						'Search',
						array(
							'class' => 'navbar-form',
							'role' => 'search',
							'inputDefaults' => array(
								'class' => 'form-control input-lg',
								'label' => false,
								'div' => array(
									'class' => 'form-group'
								),
								'wrapInput' => false
							)
						)
					);
				?>
				<?php echo $this->Form->input('document', array('placeholder' => __('R.U.T.'))); ?>
				<?php echo $this->Form->input('company', array('placeholder' => __('Razón Social'))); ?>
				<?php echo $this->Form->input('processed', array('placeholder' => __('Fecha'))); ?>
				<?php echo $this->Form->button(__('Buscar'), array('type' => 'submit', 'class' => 'btn btn-primary btn-lg')); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</nav>
	
	<br />

	<div class="row">
		<div class="col-lg-12">

			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th class="text-center"><?php echo __('Tienda'); ?></th>
						<th class="text-center"><?php echo __('Fecha'); ?></th>

						<th class="text-center"><?php echo __('Tipo'); ?></th>
						<th class="text-center"><?php echo __('# DTE'); ?></th>

						<th class="text-center"><?php echo __('Razón Social'); ?></th>
						<th class="text-center"><?php echo __('R.U.T.'); ?></th>
						<th class="text-center"><?php echo __('# OC'); ?></th>
						<th class="text-center"><?php echo __('# GD'); ?></th>
						<th class="text-center"><?php echo __('# PVT'); ?></th>
						<th class="text-center"><?php echo __('Herramientas'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ($type == 'dte' && empty($docs)): ?>
					<tr>
						<td colspan="11">
							<div class="alert alert-info text-center">
								<?php echo __('No hay documentos por conciliar.'); ?>
							</div>
						</td>
					</tr>
				<?php elseif (empty($docs)): ?>
					<tr>
						<td colspan="11">
							<div class="alert alert-danger text-center">
								<?php echo __('No hay documentos conciliados.'); ?>
							</div>
						</td>
					</tr>
				<?php endif; ?>
				<?php foreach ($docs AS $row): ?>
					<tr class="small">
						<td class="text-center"><?php echo $this->Form->checkbox('doc.' + $row['Doc']['id'], array('class' => 'checkbox')); ?></td>
						<td class="text-center"><?php echo $row['Store']['cod']; ?></td>
						<td class="text-center"><?php echo $this->Time->format('d/m/Y', $row['Doc']['processed']); ?></td>

						<td class="text-center"><?php echo $row['Type']['alias']; ?></td>
						<td class="text-center"><?php echo $row['Doc']['number']; ?></td>

						<td><?php echo $row['Doc']['company'] ? $row['Doc']['company'] : '--'; ?></td>
						<td><?php echo $row['Doc']['document'] ? $row['Doc']['document'] : '--'; ?></td>
						<td class="text-right"><?php echo $row['Doc']['noc'] ? $row['Doc']['noc'] : '--'; ?></td>
						<td class="text-right"><?php echo $row['Doc']['ngd'] ? $row['Doc']['ngd'] : '--'; ?></td>
						<td class="text-right"><?php echo $row['Doc']['npvt'] ? $row['Doc']['npvt'] : '--'; ?></td>
						<td>

							<div class="btn-group pull-right">
								<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php echo __('Opciones'); ?> <span class="caret"></span>
								</button>
							<?php
								$options = array();

								$options[] = $this->Html->link(__('Editar'), array('controller' => 'docs', 'action' => 'edit', $row['Doc']['id']));

								if ($row['Doc']['match']) {
									$options[] = $this->Html->link(__('Imprimir'), array());
									$options[] = $this->Html->link(__('Enviar'), array());
								}

								$options[] = $this->Html->link(__('Eliminar'), array('controller' => 'docs', 'action' => 'delete'), array(), __('¿Realmente deseas eliminar el documento?'));

								echo $this->Html->nestedList($options, array('class' => 'dropdown-menu pull-right', 'role' => 'menu'));
							?>
							</div>
							
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>