<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap');
		echo $this->Html->css('/js/select2/select2');
		echo $this->Html->css('select2-bootstrap');
		echo $this->Html->script('jquery');
		echo $this->Html->script('bootstrap');
		echo $this->Html->script('select2/select2');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

	<?php echo $this->Html->css(array('/js/fancybox/jquery.fancybox.css')); ?>
	<?php echo $this->Html->script(array('jquery.elevateZoom-3.0.8.min.js', 'fancybox/jquery.fancybox.pack.js')); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#previewDocument, .preview').on('load', function(){
				$(this).elevateZoom({
					zoomType: 'inner',
					cursor: 'crosshair'
				});
			});

			$('.fancybox').fancybox();
		});
	</script>
	<style type="text/css">
		tfoot .pagination {
			margin: 0;
		}
		.text-muted {
			color: #ccc;
		}
	</style>
</head>
<body>

<div class="container">
<?php if (AuthComponent::user('id')): ?>
	<div class="row">
		<div class="col-lg-6">
			<h1>
				<span class="brand"><?php echo $this->Html->image('logo.jpeg', array('width' => 150, 'alt' => __('Sodimac'))); ?></span>
				<?php
					echo $this->Form->select(
						'Store.id',
						$stores_users,
						array(
							'empty' => array(
								0 => __('Todas las tiendas')
							),
							'class' => 'input-lg',
							'default' => CakeSession::read('StoreActive'),
							'onchange' => 'window.location.href = \'' . $this->Html->url('/') . '?active_store=\' + this.options[this.selectedIndex].value;'
						)
					);
				?>
			</h1>
		</div>

		<div class="col-lg-6">
			<br class="clearfix" />

			<small class="profile pull-right">
				<?php echo __('%s (%s)', AuthComponent::user('name'), AuthComponent::user('profile')); ?>
				-
				<?php echo $this->Html->link(__('Salir'), array('admin' => false, 'controller' => 'users', 'action' => 'logout')); ?>
			</small>

			<br class="clearfix" />
			<br class="clearfix" />

			<?php echo $this->Html->link(__('Importar documentos'), array('controller' => 'docs', 'action' => 'import'), array('class' => 'btn btn-primary pull-right')); ?>

			<br class="clearfix" />
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<nav class="navbar navbar-default" role="navigation">
				<div class="container-fluid">

					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav">
							<li>
								<?php echo $this->Html->link(__('Portada'), '/'); ?>
							</li>
							<li>
								<?php echo $this->Html->link(__('Conciliación Manual'), array('controller' => 'docs', 'action' => 'manual')); ?>
							</li>
						<?php if (in_array(AuthComponent::user('profile'), array('developer', 'admin'))): ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<?php echo __('Configuración'); ?>
									<span class="caret"></span>
								</a>

								<ul class="dropdown-menu" role="menu">
									<li>
										<?php echo $this->Html->link(__('Tiendas'), array('admin' => true, 'controller' => 'stores')); ?>
									</li>
									<li>
										<?php echo $this->Html->link(__('Usuarios'), array('admin' => true, 'controller' => 'users')); ?>
									</li>
								<?php if (AuthComponent::user('profile') == 'developer'): ?>
									<li class="divider"></li>
									<li>
										<?php echo $this->Html->link(__('Tipos de documentos'), array('admin' => true, 'controller' => 'docs', 'action' => 'types')); ?>
									</li>
								<?php endif; ?>
								</ul>
							</li>
						<?php endif; ?>
						</ul>
					</div>

				</div>
			</nav>
		<div>
	</div>
<?php endif; ?>

	<div class="row">
		<div class="col-lg-12">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</div>

</body>
</html>
<?php echo $this->element('sql_dump'); ?>