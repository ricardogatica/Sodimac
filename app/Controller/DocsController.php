<?php

	set_time_limit(0);

	App::uses('AppController', 'Controller');

	class DocsController extends AppController {
		public $components = array('Paginator');

		public $details = array();

		public function ftp() {

			$from = WWW_ROOT . 'pdf';
			$to = '\\';

			if (DS === '/') {
				exec('sh ' . WWW_ROOT . 'ftpcmd.sh ' . $from . ' ' . $to, $output);
			}
			else {
				// http://www.dostips.com/DtTipsFtpBatchScript.php
				// http://nixcraft.com/showthread.php/12018-Windows-FTP-Upload-Script-and-Scheduled-Job
				exec('cmd ', $output);
			}

			debug($output);
			die;
		}

		public function index($type = 'matched') {
			$conditions = array();

			if ($this->request->data) {
				$this->redirect(array($type, '?' => array_filter($this->request->data['Search'])));
			}
			else {
				$this->request->data['Search'] = $this->request->query;

				$c = 1;
				foreach ($this->request->query AS $key => $value) {
					if ($key == 'processed') {
						$date = explode(' - ', $value);

						if (count($date) > 1) {
							$between = array();
							foreach ($date AS $d) {
								$between[] = implode('-', array_reverse(preg_split('/[-|\/]/', $d)));
							}

							$conditions['DATE(Doc.processed) BETWEEN ? AND ?'] = $between;
						}
						else {
							$conditions['DATE(Doc.processed)'] = implode('-', array_reverse(preg_split('/[-|\/]/', current($date))));
						}

						$c++;
						break;
					}

					$conditions['Doc.' . $key . ' LIKE'] = '%' . $value . '%';
				};
			}

			if (!Configure::read('debug'))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);

			$conditions['Doc.exported'] = 0;

			if ($type == 'matched') {
				$conditions['Doc.dte'] = 1;
				$conditions['Doc.matched'] = 1;
			}
			else {
				$conditions['Doc.dte'] = array(1, 0);
				$conditions['Doc.matched'] = 0;
				$conditions['Doc.mismatched'] = 1; // Se muestran documentos que no han sido match
			}

			$this->set(compact('type'));

			$this->paginate = array(
				'conditions' => $conditions,
				'fields' => array(
					'id',
					'parent_id',
					'store_id',
					'type_id',
					'processed',
					'matched',
					'printable',
					'sendable',
					'to_export',
					'dte',
					'number',
					'company',
					'document',
					'payment',
					'noc',
					'ngd',
					'ngd_0',
					'ngd_1',
					'npvt',
					'npvt_0',
					'npvt_1',
					'npvt_2',

					'danger'
				),
				'contain' => array(
					'Type.alias',
					'Store.cod'
				),
				'order' => 'Doc.id ASC'
			);

			$docs = $this->Paginator->paginate('Doc');

			$this->set(compact('docs'));
		}

		public function details($id = null, $matched = false, $dte = false) {
			if (!empty($this->details)) {
				$this->set($this->details);
				return $this->details;
			}

			$conditions = array(
				'Doc.id' => $id
			);

			if ($matched)
				$conditions['Doc.matched'] = 1;

			if ($dte)
				$conditions['Doc.dte'] = 1;

			if (!Configure::read('debug'))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);

			$details = $this->Doc->find(
				'first',
				array(
					'conditions' => $conditions,
					'contain' => array(
						'Store',
						'Type'
					)
				)
			);

			if (empty($details)) {
				$this->Session->setFlash(__('Documento no existe o no esta asociado a las tiendas designadas a su usuario.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-warning'));
				$this->redirect('/');
			}

			if ($matched && !$details['Doc']['matched']) {
				$this->Session->setFlash(__('El documento no ha sido conciliado.'));
				$this->redirect('/');
			}

			// Se buscan todos los documentos cedibles asociados.
			$conditions = array(
				'Doc.parent_id' => $id,
				'Doc.dte' => 0
			);

			if ($matched)
				$conditions['Doc.matched'] = 1;

			if (!Configure::read('debug'))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);

			$documents = $this->Doc->find(
				'all',
				array(
					'conditions' => $conditions,
					'contain' => array(
						'Store',
						'Type'
					)
				)
			);

			// Se buscan todas las imagenes.
			$images = array();

			if (!empty($details['Doc']['images'])) {
				foreach ($details['Doc']['images'] AS $image) {
					$images[] = $image;
				}
			}

			if (!empty($documents)) {
				foreach ($documents AS $row) {
					if (!empty($row['Doc']['images'])) {
						foreach ($row['Doc']['images'] AS $image) {
							$images[] = $image;
						}
					}
				}
			}
	
			// Se buscan todos los posibles documentos cedibles que podrías hacer match
			$potential = array();
			//$potential = $this->match(false, array('Doc.id' => $details['Doc']['id']))

			$data = $this->details = compact('details', 'documents', 'images', 'potential');

			$this->set($data);

			return $data;
		}

		public function edit($id = null) {
			$data = $this->details($id);

			if (!empty($this->request->data)) {
				$this->Doc->id = $data['details']['Doc']['id'];
				if ($this->Doc->save($this->request->data)) {
					$this->Session->setFlash(__('Se módifico exitosamente el documento tipo %s', $data['details']['Type']['name']), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));

					if ($data['details']['Doc']['matched']) {
						$this->unmatch($data['details']['Doc']['id']);
						$this->Session->setFlash(__('Se modifico exitosamente el documento y se han separados los documentos conciliados.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-warning'));
					}
				}
				else {
					$this->Session->setFlash(__('Se ha producido un error al intentar editar el documento, intentalo más tarde.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'));
				}

				$this->redirect($this->referer());
			}
			else {
				$this->request->data['Doc'] = $data['details']['Doc'];
			}

			$this->Doc->Type->virtualFields['name'] = 'CONCAT(Type.alias,\' (\',Type.name,\')\')';

			$types = $this->Doc->Type->find(
				'list',
				array(
					'fields' => array(
						'Type.id',
						'Type.name'
					)
				)
			);

			$this->set(compact('types'));
		}

		public function edit_matched($id = null) {
			$data = $this->details($id, null, true);

			if (in_array(AuthComponent::user('profile'), array('admin', 'conciliado'))) {
				$this->view = 'edit_conciliador';
			}

			if (!empty($this->request->data)) {
				foreach ($this->request->data['Doc'] AS $post) {
					$this->Doc->id = $post['id'];
					if ($this->Doc->save($post)) {
						$this->Session->setFlash(__('Se módifico exitosamente el documento.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));

						if ($post['matched']) {
							$this->unmatch($post['id']);
							$this->Session->setFlash(__('Se modifico exitosamente el documento y se han separados los documentos conciliados.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-warning'));
						}
					}
					else {
						$this->Session->setFlash(__('Se ha producido un error al intentar editar el documento, intentalo más tarde.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'));
					}

					$this->redirect($this->referer());
				}
					
			}
			else {
				//$this->request->data['Doc'] = $data['details']['Doc'];
			}

			$docs = array();
			$docs[] = $data['details'];

			if (!empty($data['documents'])) {
				$docs = array_merge($docs, $data['documents']);
			}

			foreach ($docs AS $row) {
				if (empty($this->request->data['Doc'][$row['Doc']['id']])) {
					$this->request->data['Doc'][$row['Doc']['id']] = $row['Doc'];
				}
			}

			$this->set(compact('docs'));

			$this->Doc->Type->virtualFields['name'] = 'CONCAT(Type.alias,\' (\',Type.name,\')\')';

			$types = $this->Doc->Type->find(
				'list',
				array(
					'fields' => array(
						'Type.id',
						'Type.name'
					)
				)
			);

			$this->set(compact('types'));
		}

		/**
		 * Método que permite agregar un respaldo no conciliado en un DTE.
		 */
		public function add($id = null) {
			$this->details($id, null, true);

			if (!empty($this->request->data['Push'])) {
				foreach ($this->request->data['Push'] AS $value) {
					if (!empty($value))
						$this->push($value, $id);
				}

				$this->Session->setFlash(__('Se han unido los respaldos a la DTE.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));
				$this->redirect($this->referer());
			}

			if (!empty($this->request->data['Doc'])) {
				$conditions = array(
					'Doc.dte' => 0,
					'Doc.matched' => 0,
					'Doc.parent_id' => 0
				);

				if (!empty($this->request->data['Doc']['type_id']))
					$conditions['Doc.type_id'] = $this->request->data['Doc']['type_id'];

				if (!empty($this->request->data['Doc']['number']))
					$conditions['Doc.number'] = $this->request->data['Doc']['number'];

				$documents = $this->Doc->find(
					'all',
					array(
						'conditions' => $conditions,
						'contain' => array(
							'Type.alias',
							'Store.cod'
						)
					)
				);

				$this->set(compact('documents'));
			}

			$this->Doc->Type->virtualFields['name'] = 'CONCAT(Type.alias,\' (\',Type.name,\')\')';

			$types = $this->Doc->Type->find(
				'list',
				array(
					'fields' => array(
						'Type.id',
						'Type.name'
					)
				)
			);

			$this->set(compact('types'));

			$potentials = $this->potential_matches_dte($id);
		}

		public function delete($id = null) {
			$this->details($id);

			$this->Doc->delete($id);
			$this->Doc->deleteAll(array('parent_id' => $id));
			$this->Session->setFlash(__('Se ha eliminado el documento.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));
			$this->redirect($this->referer());
		}

		/**
		 * Método que permite la búsqueda de los documentos a través:
		 *
		 * - RUT
		 * - Razón Social
		 * - Fecha de procesamiento
		 */
		public function search($dte = true) {



		}

		/**
		 * Método que exporta a PDF los DTE conciliados
		 */
		public function pdf($id = null, $action = null) {
			$data = $this->details($id, true, true);

			if (empty($data['images'])) {
				$this->Session->setFlash(__('El DTE no tiene imagen.'));
				$this->redirect('/');
			}
	
			$pdf = '<html>'
			. '<head>'
			. '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
			. '</head>'
			. '<body>'
			;

			foreach ($data['images'] AS $image) {
				if (!file_exists($image['path']))
					continue;

				list($width, $height, $type, $attr) = getimagesize($image['path']);

				$min_width = 700;
				$min_height = 1100;
				
				$ratio = $width / $min_width;
				if ($height > $min_height)
					$ratio = $height / $min_height;

				$ratio = max($ratio, 1.0);

				$img_width = (int)($width / $ratio);
				$img_height = (int)($height / $ratio);

				if ($img_width < $min_width) {
					if ($height < 1600) {
						$ratio = $height / $min_height;
					}
					else if ($height < 2000) { // Revisar esto por que no puede ser la imagen más ancha
						$ratio = $height / ($min_height);
					}

					$img_width = $width / $ratio;

					$pages = (($height / $ratio) / $min_height);

					$pages = round($pages);
				}
				else if ($img_width > $min_width) {
					$ratio = $img_width / $min_width;
					$img_width = $img_width / $ratio;
				}

				$pdf.= '<center><img src="' . Router::url($image['normal'], true) . '" style="width:' . $img_width . 'px;"></center>'
				. '<div style="clear:both;"></div>'
				. '<pd4ml:page.break>'
				;
			}

			$pdf.= '</body>'
			. '</html>'
			;

			$this->folderTmp = 'pdf' . DS . date('Y', strtotime($data['details']['Doc']['processed'])) . DS . date('m', strtotime($data['details']['Doc']['processed'])) . DS . date('d', strtotime($data['details']['Doc']['processed']));
			$path = $dir = '';
			foreach (explode(DS, $this->folderTmp) AS $folder) {
				$path.= DS . $folder;
				if (!in_array($folder, array('pdf')) && !file_exists(WWW_ROOT . $path) && !is_dir(WWW_ROOT . $path)) {
					mkdir(WWW_ROOT . $path);
					chmod(WWW_ROOT . $path, 0777);
				}
			}

			$pd4ml	= ROOT . DS . 'vendors' . DS . 'pd4ml' . DS . '3.9.3' . DS . 'pd4ml.jar';
			$tmp	= WWW_ROOT . $this->folderTmp . DS . $data['details']['Doc']['number'] . '.html';
			$down	= WWW_ROOT . $this->folderTmp . DS . $data['details']['Doc']['number'] . '.pdf';

			file_put_contents($tmp, $pdf);

			if ($action == 'export') {
				$this->folderExport = 'vendors' . DS . 'processed' . DS . 'CUSTODIA' . DS . $data['details']['Store']['cod'] . DS . date('Y', strtotime($data['details']['Doc']['processed'])) . DS . date('m', strtotime($data['details']['Doc']['processed'])) . DS . date('d', strtotime($data['details']['Doc']['processed']));
				$path = $dir = '';
				foreach (explode(DS, $this->folderExport) AS $folder) {
					$path.= DS . $folder;
					if (!in_array($folder, array('vendors', 'processed')) && !file_exists(ROOT . $path) && !is_dir(ROOT . $path)) {
						mkdir(ROOT . $path);
						chmod(ROOT . $path, 0777);
					}
				}

				$down = ROOT . DS . $this->folderExport . DS . $data['details']['Doc']['number'] . '.pdf';
			}

			system("java -jar {$pd4ml} file:{$tmp} {$down}", $out);

			if ($action == 'export') {
				$filename = explode('.', basename($down));
				rename($down, $down = dirname($down) . DS . '(' . $filename['0'] . ').'.$filename['1']);
			}

			sleep(1);

			return $down;
		}

		public function doc_send($id) {
			$data = $this->details($id, true, true);

			$this->Doc->id = $data['details']['Doc']['id'];
			$this->Doc->saveField('sent', 1);

			$this->Session->setFlash(__('Se ha enviado el documento exitosamente.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));
			$this->redirect('/');
		}

		public function doc_print($id) {
			$this->autoRender = false;
			$data = $this->details($id, true, true);

			$this->Doc->id = $data['details']['Doc']['id'];
			$this->Doc->saveField('printed', 1);

			$path = $this->pdf($data['details']['Doc']['id']);

			$this->response->file($path, array('download' => false, 'name' => basename($path)));
		}

		public function doc_export($id = null) {
			$data = $this->details($id, true, true);

			if ($data['details']['Doc']['to_export']) {
				$this->pdf($data['details']['Doc']['id'], 'export');

				$this->Doc->id = $id;
				$this->Doc->saveField('exported', 1);

				$this->Session->setFlash(__('El archivo se ha exportado correctamente.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));
				$this->redirect(array('controller' => 'docs', 'action' => 'index', 'matched'));
			}
			else {
				$this->Session->setFlash(__('No se puede exportar el archivo con Número DTE %s ya que no ha sido impreso o enviado.', $data['details']['Doc']['number']), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-warning'));
				$this->redirect(array('controller' => 'docs', 'action' => 'index', 'matched'));
			}
		}

		/**
		 * Método que permite el envío masivo de los documentos conciliados.
		 */
		public function bulk_send() {
			$this->Session->setFlash(__('Se ha enviararon los documentos de forma exitosa.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));
			$this->redirect('/');
		}

		/**
		 * Método que permite la impresión masiva de los documentos conciliados.
		 */
		public function bulk_print() {

		}

		/**
		 * Método que permite la exportación masiva de los documentos conciliados.
		 */
		public function bulk_export() {
			$conditions = array(
				'Doc.matched' => 1,
				'Doc.dte' => 1,
				'Doc.exported' => 0,
				'OR' => array(
					'Doc.printed' => 1,
					'Doc.sent' => 1
				)
			);

			if (!Configure::read('debug'))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);
			
			$docs = $this->Doc->find(
				'all',
				array(
					'conditions' => $conditions
				)
			);

			foreach ($docs AS $row) {
				$this->pdf($row['Doc']['id'], 'export');
				$this->details = array();

				$this->Doc->id = $row['Doc']['id'];
				$this->Doc->saveField('exported', 1);
			}

			if (empty($docs)) {
				$this->Session->setFlash(__('No hay DTE para respaldar.'));
			}
			else {
				$this->Session->setFlash(__('Se han respaldado todos los DTE.'));
			}

			$this->redirect('/');
		}

		public function import() {
			$this->autoRender = false;

			$stores = $this->Doc->Store->find(
				'list',
				array(
					'fields' => array(
						'Store.id',
						'Store.cod'
					)
				)
			);

			$types = $this->Doc->Type->find(
				'list',
				array(
					'fields' => array(
						'Type.id',
						'Type.alias'
					)
				)
			);

			\App::uses('Xml', 'Utility');

			$xmls = Hash::merge(
				glob(WWW_ROOT . 'xml' . DS . 'dte' . DS . '*' . DS . '*' . DS . '*' . DS . '*' . DS . '*' . DS . '*.xml'),
				glob(WWW_ROOT . 'xml' . DS . 'docs' . DS . '*' . DS . '*' . DS . '*' . DS . '*' . DS . '*' . DS . '*.xml')
			);

			$imports = 0;

			foreach ($xmls AS $xml_file) {

				if (!file_exists($xml_file) && is_dir($xml_file))
					continue;

				if ($this->Doc->findByFileXml($xml_file))
					continue;

				$body = file_get_contents($xml_file);
				$xml = \Xml::toArray(\Xml::build($body));

				$dte = 0;
				if (preg_match('/(dte)/', $xml_file))
					$dte = 1;

				$data = current(current($xml['Documents']));

				$case_id = 0;

				if (!empty($data)) {

					$doc = array(
						'dte' => $dte,
						'file_xml' => $xml_file,
						'content' => $body,
						'serialize' => serialize($xml),
					);

					if (!empty($data['_CodigoTienda']))
						$doc['store_id'] = (int)array_search((int)$data['_CodigoTienda'], $stores);

					if (!empty($data['_TipoDocumento']))
						$doc['type_id'] = array_search($data['_TipoDocumento'], $types);

					if (!empty($data['_NumeroDocumento']))
						$doc['number'] = ltrim($data['_NumeroDocumento'], '0');

					if (!empty($data['_NombreRazonSocial']))
						$doc['company'] = $data['_NombreRazonSocial'];

					if (!empty($data['_RUTCliente']))
						$doc['document'] = ltrim($data['_RUTCliente'], '0');

					if (!empty($data['_NroOC'])) {
						$doc['noc'] = ltrim($data['_NroOC'], '0');

						$doc['noc_0'] = (int)$data['_NroOC'];
					}

					if (!empty($data['_NroGuiaDespacho'])) {
						$doc['ngd'] = $data['_NroGuiaDespacho'];

						$ngd = explode('-', $data['_NroGuiaDespacho']);
						$doc['ngd_0'] = ltrim($ngd[0], '0');
						$doc['ngd_1'] = ltrim($ngd[1], '0');
					}

					if (!empty($data['_NroPVT'])) {
						$doc['npvt'] = $data['_NroPVT'];

						$npvt = explode('/', $data['_NroPVT']);
						$doc['npvt_0'] = ltrim($npvt[0], '0');
						$doc['npvt_1'] = ltrim($npvt[1], '0');
						$doc['npvt_2'] = $npvt[2];
					}

					if (!empty($data['_CondicionPago']))
						$doc['payment'] = $data['_CondicionPago'];

					if (!empty($data['_Lote']))
						$doc['lote'] = $data['_Lote'];

					if (!empty($data['_FechaProcesamiento']))
						$doc['processed'] = date('Y-m-d H:i:s', strtotime($data['_FechaProcesamiento']));

					if (!empty($doc['processed']) && !empty($doc['lote'])) {
						$path = array(
							'img',
							($dte ? 'dte' : 'docs'),
							$data['_CodigoTienda'],
							date('Y', strtotime($doc['processed'])),
							date('m', strtotime($doc['processed'])),
							date('d', strtotime($doc['processed'])),
							date('His', strtotime($doc['processed']))
						);

						$path = WWW_ROOT . implode(DS, $path);

						if (is_dir($path)) {
							$file_name = str_replace('.xml', '', basename($xml_file));
							$path = $path . DS . '{' . $file_name . '*.jpg}';
							$images = glob($path, GLOB_BRACE);

							if ($images)
								$doc['images'] = serialize($images);
						}
					}

					$this->Doc->id = null;
					$this->Doc->create();
					$this->Doc->set($doc);

					if ($this->Doc->save($doc)) {
						$imports++;

						$doc_id = $this->Doc->id;

						// Se guarda los documentos asociados a la DTE Venta directa por canje
						if (!empty($data['_Documentos'])) {
							if (!is_array($data['_Documentos']))
								$data['_Documentos'] = array($data['_Documentos']);

							foreach ($data['_Documentos'] AS $document) {
								$documents = array(
									'parent_id' => $doc_id,
									'dte' => 1,
									'number' => $document
								);

								$this->Doc->id = null;
								$this->Doc->create();
								$this->Doc->save($documents);
							}
						}
					}		
				}
			}

			$type = 'dte';
			$message = __('Se han importado %d DTE.', $imports);
			if ($matched = $this->match()) {
				$message.= ' ' . __('Se han conciliado %d DTE', $matched);
				$type = 'matched';
			}

			$this->Session->setFlash($message, 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-info'));

			$this->redirect(array('controller' => 'docs', 'action' => 'index', $type));
		}

		public function manual() {
			if (!empty($this->request->data)) {
				$dte = false;

				if ($this->request->data['dte']) {
					foreach ($this->request->data['dte'] AS $dte) {
						if ($dte != 0) {
							$docs = array();
							if (!empty($this->request->data['doc'])) {
								foreach ($this->request->data['doc'] AS $doc) {
									if ($doc != 0) {
										$docs[] = $doc;
										$this->push($doc, $dte);
									}
								}
							}
							break;
						}
					}
				}

				if (!$dte || empty($docs)) {
					$this->Session->setFlash(__('Se deben seleccionar los documentos que se desean fusionar.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-warning'));
					$this->redirect(array('controller' => 'docs', 'action' => 'manual'));
				}

				$this->Doc->id = $dte;
				$this->Doc->saveField('matched', 1);

				$this->Doc->updateAll(array('Doc.matched' => 1), array('Doc.id' => $docs));

				$this->Session->setFlash(__('Se ha fusionado el documento exitosamente.'), 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-success'));
				$this->redirect(array('controller' => 'docs', 'action' => 'manual'));
			}

			$conditions = array(
				'Doc.matched' => 0,
				'Doc.mismatched' => 1,
				'Doc.dte' => 1
			);

			if (!Configure::read('debug'))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);

			$dtes = $this->Doc->find(
				'all',
				array(
					'conditions' => $conditions,
					'contain' => array(
						'Store.cod',
						'Type.alias'
					)
				)
			);

			$conditions = array(
				'Doc.matched' => 0,
				'Doc.mismatched' => 1,
				'Doc.dte' => 0
			);

			if (!Configure::read('debug'))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);

			$docs = $this->Doc->find(
				'all',
				array(
					'conditions' => $conditions,
					'contain' => array(
						'Store.cod',
						'Type.alias'
					)
				)
			);

			$this->set(compact('dtes', 'docs'));
		}

		/**
		 * Método que permite asociar un documento con otro
		 */
		public function push($origin_id = 0, $destiny_id = 0) {
			$data = $this->details($destiny_id);
			$this->Doc->updateAll(array('parent_id' => $destiny_id, 'matched' => 1), array('Doc.id' => $origin_id));
		}

		public function potential_matches_dte($id = null) {
			return $this->potential_matches(array('Doc.id' => $id));
		}

		public function potential_matches($conditions = array()) {
			// Traigo todo los dte que no tienen documentos asociados
			$this->Doc->virtualFields['cedibles'] = '(SELECT COUNT(id) FROM docs WHERE parent_id = Doc.id AND dte = 0)';

			$conditions_default = array(
				'Doc.matched' => 0,
				'Doc.dte' => 1,
				'Doc.cedibles' => 0
			);

			$conditions = Hash::merge($conditions_default, $conditions);

			if (!Configure::read('debug') && !empty($this->stores_users_active))
				$conditions['Doc.store_id'] = array_keys($this->stores_users_active);

			$docs = $this->Doc->find(
				'threaded',
				array(
					'conditions' => $conditions,
					'fields' => array(
						'id',
						'parent_id',
						'type_id',
						'store_id',
						'number',
						'company',
						'payment',
						'noc',
						'ngd',
						'ngd_0',
						'ngd_1',
						'npvt',
						'npvt_0',
						'npvt_1',
						'npvt_2',
						'lote'
					)
				)
			);

			unset($this->Doc->virtualFields['cedibles']);

			$matches = array();
			foreach ($docs AS $row) {
				// Se realizan las condiciones para buscar los cedibles.
				$conditions = array();

				// Factura de canje
				if ($row['Doc']['store_id'] == 81) {
					$conditions['type_id'] = 4;
					$conditions['number'] = Hash::extract($row['children'], '{n}.Doc.number'); // Buscamos todos los documentos asociados.
				}
				else {
					// Tarjeta convenio (LISTO)
					if ($row['Doc']['type_id'] == 1 && $row['Doc']['number'] && preg_match('/(GIFT CORP SPA)/Uis', $row['Doc']['company']) && $row['Doc']['noc'] && $row['Doc']['ngd'] && $row['Doc']['npvt'] && !$row['Doc']['npvt_0'] && !$row['Doc']['npvt_1'] && !$row['Doc']['npvt_2']) {
						$conditions['type_id'] = 4; // Guía de despacho
						// Podemos buscar tambien por el segundo paramatero de la guía de despacho
						$conditions['number'] = $row['Doc']['ngd_1'];
						$conditions['noc'] = $row['Doc']['noc'];
					}
					// Venta directa anticipada, se debe realizar match por el código de tienda
					else if ($row['Doc']['type_id'] == 1 && $row['Doc']['number'] && $row['Doc']['noc'] && $row['Doc']['ngd'] && !$row['Doc']['ngd_0'] && !$row['Doc']['ngd_1'] && $row['Doc']['npvt'] && in_array($row['Doc']['npvt_2'], array('D', 'DE', 'DP'))) {
						$conditions['type_id'] = 3; // Orden de compra
						$conditions['number'] = $row['Doc']['noc'];
					}
					// Venta anticipada, stock de bodega, se debe realizar match por el código de tienda
					else if ($row['Doc']['type_id'] == 1 && $row['Doc']['number'] && $row['Doc']['noc'] && $row['Doc']['ngd'] && $row['Doc']['ngd_0'] == 0 && $row['Doc']['ngd_1'] == 0 && $row['Doc']['npvt'] && $row['Doc']['npvt_2'] == 'S') {
						$conditions['type_id'] = 3; // Orden de compra
						$conditions['number'] = $row['Doc']['noc'];
					}
					// Factura automática contra entrega stock de bodega (NO SE DEFINE UNA VERSIÓN FINAL PARA MATCH)
					else if ($row['Doc']['type_id'] == 1 && $row['Doc']['number'] && $row['Doc']['ngd'] && $row['Doc']['ngd_0'] == '185' && $row['Doc']['ngd_1']) {
						$conditions['type_id'] = 4;
						$conditions['number'] = $row['Doc']['ngd_1'];
					}
					// Factura automática, guía por caja (NO SE DEFINE UNA VERSIÓN FINAL PARA MATCH)
					else if ($row['Doc']['type_id'] == 1 && $row['Doc']['number'] && $row['Doc']['ngd'] && $row['Doc']['ngd_0'] && $row['Doc']['ngd_1']) {
						$conditions['type_id'] = 4;
						$conditions['number'] = $row['Doc']['ngd_1'];
					}
					// Guía proveedor contra entrega o factura contra entrega (NO SE DEFINE UNA VERSIÓN FINAL PARA MATCH)
					else if ($row['Doc']['type_id'] == 1 && $row['Doc']['number'] && $row['Doc']['ngd'] && $row['Doc']['npvt'] && $row['Doc']['npvt_0'] && $row['Doc']['npvt_1']) {
						$conditions['type_id'] = 4;
						$conditions['number'] = $row['Doc']['ngd_1'];
					}
					// Factura por caja (LISTO)
					else if ($row['Doc']['type_id'] == 1 && $row['Doc']['number']) {
						$conditions['type_id'] = $row['Doc']['type_id'];
						$conditions['number'] = $row['Doc']['number'];
					}
					// Nota de crédito (LISTO)
					else if ($row['Doc']['type_id'] == 2 && $row['Doc']['number']) {
						$conditions['type_id'] = $row['Doc']['type_id'];
						$conditions['number'] = $row['Doc']['number'];
					}
				}

				if (empty($conditions))
					continue;

				if (!empty($conditions)) {
					$conditions['matched'] = 0; // No debe estar unido a ningún DTE (Cover)
					$conditions['dte'] = 0; // No debe ser DTE (Cover)

					$documents = $this->Doc->find(
						'all',
						array(
							'conditions' => $conditions,
							'fields' => array(
								'id',
								'type_id',
								'number',
								'noc',
								'ngd',
								'ngd_0',
								'ngd_1',
								'npvt',
								'npvt_0',
								'npvt_1',
								'npvt_2',
								'lote'
							)
						)
					);

					if (empty($documents))
						continue;

					$matches[$row['Doc']['id']]['details']['id'] = $row['Doc']['id'];

					foreach ($documents AS $document) {
						$matches[$row['Doc']['id']]['matches'][] = array(
							'id' => $document['Doc']['id']
						);
					}
				}				
			}

			return $matches;
		}

		/**
		 * Método que realiza match de los DTE con los documentos cedibles.
		 */
		public function match($cron = true, $conditions = array()) {
			$this->autoRender = false;

			if (!$cron && empty($this->stores_users_active))
				return __('No se puede realizar match manual');

			//potential_matches_dte
			$docs = $this->potential_matches($conditions);

			$matches = 0;
			foreach ($docs AS $dte) {
				if (!empty($dte['matches'])) {
					foreach ($dte['matches'] AS $match) {
						$this->Doc->id = $match['id'];
						$this->Doc->save(
							array(
								'parent_id' => $dte['details']['id'],
								'matched' => 1
							)
						);

						$matches++;
					}
					
					// Se actualiza el DTE como matcheado con sus cedibles
					$this->Doc->id = $dte['details']['id'];
					$this->Doc->saveField('matched', 1);

					// Se genera pdf automáticamente.
					if (!Configure::read('debug'))
						$this->Doc->saveField('file_pdf', $this->pdf($row['Doc']['id']));
				}
			}

			return $matches;
		}

		public function unmatch($id = null) {
			$data = $this->details($id, true);

			if (!$data['details']['Doc']['exported']) {
				if ($data['details']['Doc']['dte']) {
					$this->Doc->updateAll(array('matched' => 0), array('Doc.id' => $data['details']['Doc']['id']));
					$this->Doc->updateAll(array('matched' => 0, 'parent_id' => 0), array('parent_id' => $data['details']['Doc']['id']));
				}
				else {
					$this->Doc->updateAll(array('matched' => 0), array('Doc.id' => $data['details']['Doc']['parent_id'])); #DTE
					$this->Doc->updateAll(array('matched' => 0), array('Doc.id' => $data['details']['Doc']['id']));
					$this->Doc->updateAll(array('matched' => 0, 'parent_id' => 0), array('parent_id' => $data['details']['Doc']['parent_id']));
				}
			}			
		}

	}