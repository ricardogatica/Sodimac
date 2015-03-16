<?php

	App::uses('AppModel', 'Model');

	class Doc extends AppModel {
		public $belongsTo = array(
			'Store',
			'Type' => array(
				'className' => 'DocType'
			)
		);

		public $virtualFields = array();
		
		public $validate = array();
		
		public $search = null;
		
		/*
		 * Opciones por defecto para la consulta de los avisos
		 */
		public $default = array();
		
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);
		}

		public function find($type = 'first', array $params = array()) {
			$this->contain('Store');

			$this->virtualFields['mismatched'] = 'IF(DATE(DATE_ADD(Doc.processed, INTERVAL Store.mismatch_days DAY)) < \'' . date('Y-m-d') . '\', 1, 0)';
			$this->virtualFields['danger'] = 'IF(Doc.matched, 0, IF(DATE(DATE_ADD(Doc.processed, INTERVAL (Store.mismatch_days + Store.warning_days) DAY)) < \'' . date('Y-m-d') . '\', 1, 0))';

			$this->virtualFields['to_export'] = 'IF((Doc.matched AND Doc.printed) OR (Doc.matched AND Doc.sent), 1, 0)';

			$result = parent::find($type, $params);

			return $result;
		}

		public function afterFind(array $results, $primary = false) {
			\App::uses('CakeNumber', 'Utility');

			foreach ($results as $key => $val) {
				$images = array();
				if (!empty($val[$this->alias]['images'])) {
					foreach (unserialize($val[$this->alias]['images']) AS $i => $image) {
						$image = str_replace('\\', '/', $image);
						$image_ = explode('/', preg_replace('/(.*)\/app\/webroot/', '', $image));
						$image_normal = implode('/', $image_);
						$image_zoom = str_replace('.jpg', '_zoom.jpg', implode('/', $image_));

						if ($i == 0) {
							$results[$key][$this->alias]['preview_normal'] = $image_normal;
							$results[$key][$this->alias]['preview_zoom'] = $image_zoom;
						}

						$images[] = array(
							'id' => $i,
							'path' => $image,
							'normal' => $image_normal,
							'zoom' => $image_zoom
						);
					}
				}
				else {
					$text = str_replace(' ', '+', __('Documento sin imagen'));
					$results[$key][$this->alias]['preview_normal'] = 'http://placehold.it/800x1000&text=' . $text;
					$results[$key][$this->alias]['preview_zoom'] = 'http://placehold.it/800x1000&text=' . $text;
				}

				$results[$key][$this->alias]['images'] = $images;

				if (!empty($results[$key][$this->alias]['serialize']))
					$results[$key][$this->alias]['original'] = unserialize($results[$key][$this->alias]['serialize']);
			}

			return $results;
		}

	}