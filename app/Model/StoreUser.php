<?php

	App::uses('AppModel', 'Model');

	class StoreUser extends AppModel {
		public $useTable = 'stores_users';

		public $belongsTo = array(
			'Store'
		);
	}