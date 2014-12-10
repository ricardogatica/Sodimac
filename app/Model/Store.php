<?php

	App::uses('AppModel', 'Model');

	class Store extends AppModel {

		public $virtualFields = array(
			'name_cod' => 'CONCAT(Store.name, \' (\', Store.cod, \')\')'
		);

		public $hasAndBelongsToMany = array(
			'User' => array(
				'className' => 'Store',
				'joinTable' => 'stores_users',
				'foreignKey' => 'store_id',
				'associationForeignKey' => 'user_id',
				'dependent' => true
			)
		);
	}