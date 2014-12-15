
<div class="row">
	<div class="col-lg-12">



	</div>
</div>


<div class="row">
	<div class="col-lg-7">

		<h3 class="page-header"><?php echo __('Vista del PDF'); ?></h3>

		<!--<iframe src="<?php echo Router::url(array('controller' => 'docs', 'action' => 'pdf_view', $details['Doc']['id'])); ?>" width="100%" height="600"></iframe>-->

	</div>

	<div class="col-lg-5">

		<h3 class="page-header">
			<?php echo $this->Html->link(__('Exportar'), array('controller' => 'docs', 'action' => 'edit', $details['Doc']['id']), array('class' => 'btn btn-primary pull-right'), __('¿Esta realmente seguro de exportar el documento? Esta acción no se podrá deshacer.')); ?>
			<?php echo $this->Html->link(__('Editar'), array('controller' => 'docs', 'action' => 'edit', $details['Doc']['id']), array('class' => 'btn btn-link pull-right')); ?>
			<?php echo __('Datos'); ?>
		</h3>

		<table class="table">
			<tbody>
				<tr>
					<th><?php echo __('Tienda'); ?></th>
					<td><?php echo $details['Store']['name']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Tipo'); ?></th>
					<td><?php echo $details['Type']['name']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Numero'); ?></th>
					<td><?php echo $details['Doc']['number']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Razón Social'); ?></th>
					<td><?php echo $details['Doc']['company']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('RUT'); ?></th>
					<td><?php echo $details['Doc']['document']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Nro. OC'); ?></th>
					<td><?php echo $details['Doc']['noc']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Nro. GD'); ?></th>
					<td><?php echo $details['Doc']['ngd']; ?></td>
				</tr>
				<tr>
					<th><?php echo __('Nro. PVT'); ?></th>
					<td><?php echo $details['Doc']['npvt']; ?></td>
				</tr>
			</tbody>
		</table>

	<?php if (!empty($documents)): ?>
		<h4><?php echo __('Documentos asociados'); ?></h4>

		<table class="table">
		<?php foreach ($documents AS $row): ?>
			<tr>
				<td>#<?php echo $row['Doc']['id']; ?></td>
				<td><?php echo __('%s Nro.', $row['Type']['name']); ?></td>
				<td><?php echo $this->Html->link($row['Doc']['number'], array('iframe' => true, 'controller' => 'docs', 'action' => 'edit', $row['Doc']['id']), array('class' => 'fancybox fancybox.iframe')); ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
</div>
