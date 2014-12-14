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
</head>
<body>

<?php echo $this->Session->flash(); ?>

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</div>

</body>
</html>
<?php echo $this->element('sql_dump'); ?>