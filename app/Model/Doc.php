<?php

	App::uses('AppModel', 'Model');

	class Doc extends AppModel {
		public $belongsTo = array(
			'Store',
			'Type' => array(
				'className' => 'DocType'
			)
		);

		public function afterFind(array $results, $primary = false) {
			\App::uses('CakeNumber', 'Utility');

			foreach ($results as $key => $val) {
				if (!empty($val[$this->alias]['images'])) {
					$images = unserialize($val[$this->alias]['images']);

					$images_ = array();
					foreach ($images AS $image) {
						$image = explode(DS, preg_replace('/(.+)\/app\/webroot/', '', $image));
						$images_[] = implode('/', $image);
					}

					$results[$key][$this->alias]['images'] = $images_;
				}
			}

			return $results;
		}

	}