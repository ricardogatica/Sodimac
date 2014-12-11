<?php

	App::uses('Controller', 'Controller');

	class AppController extends Controller {
		public $components = array(
			'Cookie',
			'Session',
			'RequestHandler',
			'Security',
			'Auth' => array(
				'loginAction' => array(
					'controller' => 'users',
					'action' => 'login'
				),
				'authenticate' => array(
					'Form'
				)
			)
		);

		public $stores_users, $stores_users_active = array();

		public $helpers = array(
			'Session',
			'Html' => array(
				'className' => 'BoostCake.BoostCakeHtml'
			),
			'Form' => array(
				'className' => 'BoostCake.BoostCakeForm'
			),
			'Paginator' => array(
				'className' => 'BoostCake.BoostCakePaginator'
			),
			'Text',
			'Number'
		);

		public function beforeFilter() {
			parent::beforeFilter();

			$stores_users = array();

			if (isset($this->request->params['iframe'])) {
				$this->layout = 'iframe';
			}

			$profiles = array(
				'admin' => __('Administrador'),
				'user' => __('Usuario')
			);

			if (AuthComponent::user('id')) {
				if (AuthComponent::user('profile') == 'developer')
					$profiles['developer'] = __('Desarrollador');

				$this->loadModel('StoreUser');

				$this->StoreUser->virtualFields['name'] = 'CONCAT(\'Cod \',Store.cod, \' (\', Store.name,\')\')';

				$this->stores_users_active = $this->stores_users = $stores_users = $this->StoreUser->find(
					'list',
					array(
						'conditions' => array(
							'StoreUser.user_id' => AuthComponent::user('id')
						),
						'fields' => array(
							'Store.id',
							'StoreUser.name'
						),
						'contain' => array(
							'Store'
						),
						'recursive' => 0
					)
				);

				if (isset($this->request->query['active_store'])) {
					if ($this->request->query['active_store'] == 0) {
						CakeSession::delete('StoreActive');
					}
					else {
						CakeSession::write('StoreActive', $this->request->query['active_store']);
					}
					
					$this->redirect($this->referer());
				}

				if (CakeSession::check('StoreActive')) {
					$this->stores_users_active = array(CakeSession::read('StoreActive') => CakeSession::read('StoreActive'));
				}
			}

			$this->set(compact('stores_users', 'profiles'));
		}

	}
