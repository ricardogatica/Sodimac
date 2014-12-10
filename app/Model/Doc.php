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
				$images = array();
				if (!empty($val[$this->alias]['images'])) {
					foreach (unserialize($val[$this->alias]['images']) AS $i => $image) {
						$image = explode(DS, preg_replace('/(.+)\/app\/webroot/', '', $image));
						$image_normal = implode('/', $image);
						$image_zoom = str_replace('.jpg', '_zoom.jpg', implode('/', $image));

						if ($i == 0) {
							$results[$key][$this->alias]['preview_normal'] = $image_normal;
							$results[$key][$this->alias]['preview_zoom'] = $image_zoom;
						}

						$images[] = array(
							'normal' => $image_normal,
							'zoom' => $image_zoom
						);
					}
				}
				else {
					$text = str_replace(' ', '+', __('Documento sin imagen'));
					$results[$key][$this->alias]['preview_normal'] = 'http://placehold.it/195x300&text=' . $text;
					$results[$key][$this->alias]['preview_zoom'] = 'http://placehold.it/600x500&text=' . $text;
				}

				$results[$key][$this->alias]['images'] = $images;
			}

			return $results;
		}

	}