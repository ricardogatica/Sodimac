
<?php echo $this->Form->create('Match'); ?>
<div class="row">
	<div class="col-lg-6">
		<h2 class="page-header"><?php echo __('DTE'); ?></h2>

		<table class="table">
			<thead>
				<tr>
					<th class="text-center"></th>
					<th class="text-center"><?php echo __('Tienda'); ?></th>
					<th class="text-center"><?php echo __('Tipo'); ?></th>
					<th class="text-center">#<?php echo __('DTE'); ?></th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
			<?php if (empty($dtes)): ?>
				<tr>
					<td colspan="6" class="text-center">
						<div class="alert alert-info">
							<?php echo __('No hay DTE para conciliar.'); ?>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			<?php foreach ($dtes AS $row): ?>
				<tr>
					<td class="text-center"><?php echo $this->Form->checkbox('dte.' . $row['Doc']['id'], array('value' => $row['Doc']['id'], 'class' => 'dte')); ?></td>
					<td class="text-center"><?php echo $row['Store']['cod'] ? $row['Store']['cod'] : '--'; ?></td>
					<td class="text-center"><?php echo $row['Type']['alias'] ? $row['Type']['alias'] : '--'; ?></td>
					<td class="text-center"><?php echo $row['Doc']['number']; ?></td>
					<td class="text-center"><?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'docs', 'action' => 'edit', $row['Doc']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="col-lg-6">
		<h2 class="page-header">
			<?php echo $this->Html->link(__('Conciliar documentos'), array(), array('class' => 'submit btn btn-primary pull-right')) ?>
			<?php echo __('Respaldos'); ?>
		</h2>

		<table class="table">
			<thead>
				<tr>
					<th class="text-center"></th>
					<th class="text-center"><?php echo __('Tienda'); ?></th>
					<th class="text-center"><?php echo __('Tipo'); ?></th>
					<th class="text-center">#<?php echo __('Número'); ?></th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
			<?php if (empty($docs)): ?>
				<tr>
					<td colspan="6" class="text-center">
						<div class="alert alert-info">
							<?php echo __('No hay documentos respaldos para conciliar.'); ?>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			<?php foreach ($docs AS $row): ?>
				<tr>
					<td class="text-center"><?php echo $this->Form->checkbox('doc.' . $row['Doc']['id'], array('value' => $row['Doc']['id'], 'class' => 'doc')); ?></td>
					<td class="text-center"><?php echo $row['Store']['cod'] ? $row['Store']['cod'] : '--'; ?></td>
					<td class="text-center"><?php echo $row['Type']['alias'] ? $row['Type']['alias'] : '--'; ?></td>
					<td class="text-center"><?php echo $row['Doc']['number']; ?></td>
					<td class="text-center"><?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'docs', 'action' => 'edit', $row['Doc']['id']), array('escape' => false)); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	$(document).on('click', '.dte', function(){
		if ($(this).is('.checked')) {
			$('.dte').removeClass('checked').prop('disabled', false).parent().parent().removeClass('text-muted');
		}
		else {
			$('.dte[id=' + $(this).attr('id') + ']').addClass('checked');
			$('.dte:not([id=' + $(this).attr('id') + '])').prop('disabled', true).parent().parent().addClass('text-muted');
		}
	});

	$(document).on('click', '.submit', function(event){
		event.preventDefault();
		$('form').submit();
	});
</script>