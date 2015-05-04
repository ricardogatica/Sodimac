<?php

	App::uses('AppController', 'Controller');

	class UsersController extends AppController {

		public function admin_index() {
			$users = $this->User->find(
				'all',
				array(
					''
				)
			);

			$this->set(compact('users'));
		}

		public function admin_add() {
			$this->view = 'admin_form';

			$stores = $this->User->Store->find(
				'list'
			);

			if ($this->request->data) {
				if (!empty($this->request->data['User']['password']))
					$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);

				$this->User->set($this->request->data);
				if ($this->User->save($this->request->data)) {
					foreach ($this->request->data['StoreUser']['store_id'] AS $store_id) {
						$this->User->StoreUser->create();
						$this->User->StoreUser->save(array('user_id' => $this->User->id, 'store_id' => $store_id));
					}

					$this->Session->setFlash(__('El usuario se ha agregado de forma éxitosa.'));
					
					$this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
				}
			}

			$this->set(compact('stores'));
		}

		public function admin_edit($user_id) {
			$this->view = 'admin_form';

			$this->User->validator()->remove('password');

			$details = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'id' => $user_id
					),
					'fields' => array(
						'id',
						'name',
						'username',
						'profile'
					),
					'contain' => array(
						'Store'
					)
				)
			);

			$stores = $this->User->Store->find(
				'list'
			);

			if ($this->request->data) {
				if (empty($this->request->data['User']['password'])) {
					unset($this->request->data['User']['password']);
				}
				else {
					$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
				}

				$this->User->set($this->request->data);
				if ($this->User->save($this->request->data)) {

					$this->User->StoreUser->deleteAll(array('user_id' => $this->User->id));

					foreach ($this->request->data['StoreUser']['store_id'] AS $store_id) {
						$this->User->StoreUser->create();
						$this->User->StoreUser->save(array('user_id' => $this->User->id, 'store_id' => $store_id));
					}

					if ($this->request->data['User']['id'] == AuthComponent::user('id') && !empty($this->request->data['User']['password'])) {
						$this->Session->setFlash(__('El usuario se ha modificado la contraseña, ahora debes volver a entrar.'));
						$this->redirect(array('admin' => false, 'controller' => 'users', 'action' => 'logout'));
					}

					$this->Session->setFlash(__('El usuario se ha editado de forma éxitosa.'));
					
					$this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
				}
			}
			else {
				$this->request->data = $details;

				if (!empty($details['Store'])) {
					$this->request->data['StoreUser']['store_id'] = Hash::extract($details['Store'], '{n}.id');
				}
			}

			$this->set(compact('stores'));
		}

		public function admin_delete($user_id) {
			$this->User->delete($user_id);
			$this->Session->setFlash(__('El usuario se ha eliminado de forma éxitosa.'));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		public function login() {
			if ($this->Auth->login()) {
				$this->redirect('/');
			}
		}

		public function logout() {
			$this->Auth->logout();
			$this->redirect('/');
		}

	}