
	<div class="page-header">
		<h2>
			<?php echo $this->Html->link(__('Agregar nuevo usuario'), array('controller' => 'users', 'action' => 'add'), array('class' => 'btn btn-primary pull-right')); ?>
			<?php echo __('Usuarios'); ?>
		</h2>
	</div>

	<div class="row">
		<div class="col-lg-12">

			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th><?php echo __('Nombre'); ?></th>
							<th><?php echo __('Usuario'); ?></th>
							<th><?php echo __('Perfil'); ?></th>
							<th><?php echo __('Opciones'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($users AS $row): ?>
						<tr>
							<td><?php echo $row['User']['name']; ?></td>
							<td><?php echo $row['User']['username']; ?></td>
							<td><?php echo $row['User']['profile']; ?></td>
							<td>
								<div class="btn-group">
									<?php
										echo $this->Html->link(
											__('Editar'),
											array(
												'admin' => true,
												'controller' => 'users',
												'action' => 'edit',
												$row['User']['id']
											),
											array(
												'escape' => false,
												'class' => 'btn btn-default'
											)
										);
									?>
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li>
											<?php
												echo $this->Html->link(
													$this->Html->tag(
														'i',
														'',
														array(
															'class' => 'glyphicon glyphicon-trash'
														)
													)
													. ' '
													. __('Eliminar'),
													array(
														'admin' => true,
														'controller' => 'users',
														'action' => 'delete',
														$row['User']['id']
													),
													array(
														'escape' => false
													)
												);
											?>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>

		</div>
	</div>

