
<br />

<div class="btn-group btn-group-justified" role="group">
	<?php echo $this->Html->link(__('Documentos conciliados'), array('controller' => 'docs', 'action' => 'index', 'matched'), array('class' => 'btn btn-default ' . ($type == 'matched' ? 'active' : ''))); ?>
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
			<tfoot>
				<tr>
					<td colspan="4" width="30%">
						<?php
							echo $this->Form->select(
								'bulk',
								array(
									'print' => __('Imprimir'),
									'send' => __('Enviar')
								),
								array(
									'empty' => __('Acciones masivas'),
									'style' => 'width:50%;margin-right:10px;',
									'class' => 'form-control pull-left'
								)
							);

							echo $this->Form->submit(__('Ir'), array('div' => false, 'class' => 'btn btn-primary'));
						?>
					</td>
					<td colspan="2" width="20%">
						<?php echo $this->Html->link(__('Exportación masiva'), array('controller' => 'docs', 'action' => 'bulk_export'), array('class' => 'btn btn-success'), __('¿Realmente deseas exportar todos los documentos exportables?')); ?>
					</td>
					<td colspan="6" width="50%">
						<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
					</td>
				</td>
			</tfoot>
			<thead>
				<tr>
					<th></th>
					<th class="text-center"><?php echo __('Tienda'); ?></th>
					<th class="text-center"><?php echo __('Fecha'); ?></th>

					<th class="text-center"><?php echo __('Tipo'); ?></th>
					<th class="text-center"><?php echo $type == 'matched' ? __('# DTE') : __('Número'); ?></th>

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
					<td colspan="12">
						<div class="alert alert-warning text-center">
							<?php echo __('No hay documentos por conciliar.'); ?>
						</div>
					</td>
				</tr>
			<?php elseif (empty($docs)): ?>
				<tr>
					<td colspan="12">
						<div class="alert alert-info text-center">
							<?php echo __('No tienes documentos conciliados para exportar :)'); ?>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			<?php foreach ($docs AS $row): ?>
				<tr class="small <?php echo $row['Doc']['danger'] ? 'danger' : ''; ?> <?php echo ($row['Doc']['to_export']) ? 'success' : ''; ?>">
					<td class="text-center"><?php echo $this->Form->checkbox('doc.' + $row['Doc']['id'], array('class' => 'checkbox', 'value' => $row['Doc']['id'])); ?></td>
					<td class="text-center"><?php echo $row['Store']['cod']; ?></td>
					<td class="text-center"><?php echo $this->Time->format('d/m/Y', $row['Doc']['processed']); ?></td>

					<td class="text-center"><?php echo $row['Type']['alias']; ?></td>
					<td class="text-center"><?php echo $this->Html->link($row['Doc']['number'], array('controller' => 'docs', 'action' => ($row['Doc']['matched'] ? 'edit_matched' : 'edit'), $row['Doc']['id'])); ?></td>

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

							$options[] = $this->Html->link(__('Editar'), array('controller' => 'docs', 'action' => ($row['Doc']['matched'] ? 'edit_matched' : 'edit'), $row['Doc']['id']));

							if ($row['Doc']['matched']) {
								$options[] = $this->Html->link(__('Imprimir'), array('controller' => 'docs', 'action' => 'doc_print', $row['Doc']['id']), array('class' => 'fancybox print', 'target' => '_blank'));

								$options[] = $this->Html->link(__('Enviar'), array('controller' => 'docs', 'action' => 'doc_send', $row['Doc']['id']), array('class' => 'fancybox send'));

								$options[] = $this->Html->link(__('Exportar'), array('controller' => 'docs', 'action' => 'doc_export', $row['Doc']['id']), array('class' => 'fancybox'));
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

<script type="text/javascript">
	$(document).on('change', '#bulk', function(event){
		var self = $(this);

		$('.checkbox:checked').each(function(i, element){

			$(this).parent().parent().addClass('success');
			if (self.val() == 'print')
				window.open('<?php echo $this->Html->url(array('controller' => 'docs', 'action' => 'doc_print')); ?>/' + $(this).val(), 'print' + i, 'height=600,width=800');
		});		
	});

	$(document).on('click', '.print,.send', function(event){
		$(this).parent().parent().parent().parent().parent().addClass('success');
	});
</script>