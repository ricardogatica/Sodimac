
	<div class="page-header">
		<h2>
			<?php echo __('Tiendas'); ?>
		</h2>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo __('Nombre de tienda'); ?></th>
						<th><?php echo __('Código de tienda'); ?></th>
						<th><?php echo __('Opciones'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($stores AS $row): ?>
					<tr>
						<td><?php echo $row['Store']['id']; ?></td>
						<td><?php echo $row['Store']['name']; ?></td>
						<td><?php echo $row['Store']['cod']; ?></td>
						<td>
							<?php
								echo $this->Html->link(
									__('Editar'),
									array(
										'admin' => true,
										'controller' => 'stores',
										'action' => 'edit',
										$row['Store']['id']
									),
									array(
										'escape' => false,
										'class' => 'btn btn-default'
									)
								);
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>
	