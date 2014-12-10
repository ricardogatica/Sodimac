<?php

	App::uses('AppController', 'Controller');

	class StoresController extends AppController {

		public function admin_index() {
			$stores = $this->Store->find(
				'all'
			);

			$this->set(compact('stores'));
		}

		public function admin_edit($store_id) {
			$this->view = 'admin_form';

			$details = $this->Store->find(
				'first',
				array(
					'conditions' => array(
						'Store.id' => $store_id
					)
				)
			);

			if (!empty($this->request->data)) {
				$this->Store->save($this->request->data);
				$this->redirect(array('admin' => true, 'controller' => 'stores', 'action' => 'index'));
			}
			else {
				$this->request->data = $details;
			}
		}

	}