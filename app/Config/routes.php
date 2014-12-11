<?php

	Router::connect('/', array('controller' => 'docs', 'action' => 'index'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/iframe/:controller/:action/*', array('iframe' => true));

	CakePlugin::routes();

	require CAKE . 'Config' . DS . 'routes.php';
