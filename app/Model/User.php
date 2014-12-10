<?php

	App::uses('AppModel', 'Model');

	class User extends AppModel {
		public $validate;

		public $hasMany = array(
			'StoreUser' => array(
				'dependent' => true
			)
		);

		public $hasAndBelongsToMany = array(
			'Store' => array(
				'className' => 'Store',
				'joinTable' => 'stores_users',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'store_id',
				'dependent' => true
			)
		);

		public function __construct() {
			parent::__construct();

			$this->validate = array(
				'name' => array(
					'rule' => 'notEmpty',
					'message' => __('Se debe ingresar el nombre completo.')
				),
				'username' => array(
					'rule' => 'notEmpty',
					'message' => __('Se debe escojer un nombre de usuario.')
				),
				'password' => array(
					'rule' => 'notEmpty',
					'message' => __('Se debe asignar una contraseña.')
				),
				'profile' => array(
					'rule' => array('comparison', '!=', '0'),
					'allowEmpty' => false,
					'message' => __('Se debe seleccionar un perfil.')
				)
			);
		}
	}