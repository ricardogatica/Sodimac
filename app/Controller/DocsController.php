<?php

	App::uses('AppController', 'Controller');

	class DocsController extends AppController {

		public function admin_types() {

		}

		public function index($type = 'match') {
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

			if ($type == 'match') {
				$conditions['Doc.match'] = 1;
				//$conditions['Doc.dte'] = array(0,1);
			}
			else {
				//$conditions['Doc.dte'] = 1;
				$conditions['DATE(DATE_ADD(Doc.processed, INTERVAL 3 DAY)) <'] = date('Y-m-d');
			}

			$this->set(compact('type'));

			$docs = $this->Doc->find(
				'threaded',
				array(
					'conditions' => $conditions,
					'fields' => array(
						'id',
						'parent_id',
						'store_id',
						'type_id',
						'processed',
						'match',
						'printable',
						'sendable',
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
					),
					'contain' => array(
						'Type.alias',
						'Store.cod'
					),
					'order' => 'Doc.id ASC'
				)
			);

			$this->set(compact('docs'));
		}

		public function edit($id = null) {
			$details = $this->Doc->find(
				'first',
				array(
					'conditions' => array(
						'Doc.id' => $id,
						'Doc.dte' => 1,
						'Doc.store_id' => array_keys($this->stores_users_active)
					),
					'contain' => array(
						'Store',
						'Type'
					)
				)
			);

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

			$this->request->data = $details;

			$cedibles = $this->Doc->find(
				'all',
				array(
					'conditions' => array(
						'Doc.parent_id' => $id
					)
				)
			);

			$this->set(compact('details', 'types', 'cedibles'));
		}

		public function pdf($id = null) {
			$details = $this->Doc->find(
				'first',
				array(
					'conditions' => array(
						'Doc.id' => $id,
						'Doc.match' => 1,
						'Doc.dte' => 1
					),
					'contain' => array(
						'Store',
						'Type'
					)
				)
			);

			$documents = $this->Doc->find(
				'all',
				array(
					'conditions' => array(
						'Doc.match' => 1
					)
				)
			);

			$_pd4ml	= ROOT . DS . 'vendors' . DS . 'pd4ml' . DS . '3.9.3' . DS . 'pd4ml.jar';
			$_file	= ROOT . DS . 'vendors' . DS . 'tifs' . DS . 'out' . DS . 'out.html';
			
			$pdf = '<html>'
			. '<head>'
			. '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
			. '</head>'
			. '<body>'
			;
			
			foreach ($documents AS $row) {

				$images = array();
				if ($row['Doc']['images']) {
					$images = unserialize($row['Doc']['images']);

					foreach ($images AS $img) {
						if (file_exists($img)) {
							list($width, $height, $type, $attr) = getimagesize($img);

							$min_width = 700;
							$min_height = 900;
							
							$ratio = $width / $min_width;
							if ($height > $min_height)
								$ratio = $height / $min_height;
							
							$ratio = max($ratio, 1.0);

							$pdf.= '<img src="" style="width:'.$img_width.'px;">'
							. '<pd4ml:page.break>'
							;
						}
							
					}
					
				}
				


				/*
				foreach (array_keys($value) AS $number) {
					$query = 'SELECT * FROM documents_pdfs WHERE document = \'' . $document . '\' AND number = \'' . $number . '\' AND type IN (\'FAC\',\'GD\',\'OC\',\'RES\')';
					$documents_ = $this->MySQL->ExecuteSQL($query);
					
					$fac_num = $nc_num = $fac = $gd = $oc = $nc = $res = '';
					$i = 0;
					
					if (!empty($documents_) && is_array($documents_)) {
						$documents_ = count($documents_) == 1 ? array($documents_) : $documents_;

						foreach ($documents_ AS $row) {
							$img = ROOT . DS . 'vendors' . DS . 'tifs' . DS . 'out' . DS . $row['file'];
							list($width, $height, $type, $attr) = getimagesize($img);
							
							$min_width = 700;
							$min_height = 900;
							
							$ratio = $width / $min_width;
							if ($height > $min_height)
								$ratio = $height / $min_height;
							
							$ratio = max($ratio, 1.0);
							
							$img_width = (int)($width / $ratio);
							$img_height = (int)($height / $ratio);
							
							if (!empty($row['type']) && $row['type'] == 'FAC') {
								if ($fac_num != $row['fact'])
									$fac_num = $row['fact'];
								 
								$fac.= '<img src="' . $this->config['full_base_url'] . '/vendors/tifs/out/' . $row['file'] . '" style="width:'.$img_width.'px;">'
								. '<pd4ml:page.break>'
								;
							}
							
							if (!empty($row['type']) && $row['type'] == 'GD') { 
								$gd.= '<img src="' . $this->config['full_base_url'] . '/vendors/tifs/out/' . $row['file'] . '" style="width:'.$img_width.'px;">'
								. '<pd4ml:page.break>'
								;
							}
							
							if (!empty($row['type']) && $row['type'] == 'OC') { 
								$oc.= '<img src="' . $this->config['full_base_url'] . '/vendors/tifs/out/' . $row['file'] . '" style="width:'.$img_width.'px;">'
								. '<pd4ml:page.break>'
								;
							}
			
							if (!empty($row['type']) && $row['type'] == 'RES') { 
								$res.= '<img src="' . $this->config['full_base_url'] . '/vendors/tifs/out/' . $row['file'] . '" style="width:'.$img_width.'px;">'
								. '<pd4ml:page.break>'
								;
							}
			
							$i++;
						}
						
						if ($fac_num AND ($fac OR $gd OR $oc)) {
							$_down	= ROOT . DS . $this->folderPdf . DS . $fac_num . '.pdf';
							
							file_put_contents($_file, $pdf_begin.$fac.$gd.$oc.$res.$pdf_end);
							system("java -jar {$_pd4ml} file:{$_file} {$_down}");
							
							$filename = explode('.', basename($_down));
							
							rename($_down, dirname($_down) . DS . '(' . $filename['0'] . ').'.$filename['1']);
							sleep(1);
							
							//@unlink($_file);
						}
					}

					$query = 'SELECT * FROM documents_pdfs WHERE document = \'' . $document . '\' AND number = \'' . $number . '\' AND type = \'NC\'';
					$documents_nc = $this->MySQL->ExecuteSQL($query);

					if (!empty($documents_nc) && is_array($documents_nc)) {
						$documents_nc = count($documents_nc) == 1 ? array($documents_nc) : $documents_nc;
						
						foreach ($documents_nc AS $row) {
							if (!empty($row['type']) && $row['type'] == 'NC') {
								$img = ROOT . DS . 'vendors' . DS . 'tifs' . DS . 'out' . DS . $row['file'];
								list($width, $height, $type, $attr) = getimagesize($img);
								
								$min_width = 700;
								$min_height = 900;
								
								$ratio = $width / $min_width;
								if ($height > $min_height)
									$ratio = $height / $min_height;
								
								$ratio = max($ratio, 1.0);
								
								$img_width = (int)($width / $ratio);
								$img_height = (int)($height / $ratio);

								if ($nc_num != $row['document'])
									$nc_num = $row['document'];
								
								$nc.= '<img src="' . $this->config['full_base_url'] . '/vendors/tifs/out/' . $row['file'] . '" style="width:'.$img_width.'px;">'
								. '<pd4ml:page.break>'
								;
							}
						}
						if ($nc) {
							$_down	= ROOT . DS . $this->folderPdfNC . DS . $nc_num . '.pdf';
							
							file_put_contents($_file, $pdf_begin.$nc.$pdf_end);
							system("java -jar {$_pd4ml} file:{$_file} {$_down}");
							
							$filename = explode('.', basename($_down));
							
							rename($_down, dirname($_down) . DS . '(' . $filename['0'] . ').'.$filename['1']);
							sleep(1);
							
							//@unlink($_file);
						}
					}
				}
				*/
			}

			$pdf.= '</body>'
			. '</html>'
			;

			debug($pdf);
			die;
			
			$_down	= ROOT . DS . $this->folderPdf . DS . $fac_num . '.pdf';
			
			/*
			file_put_contents($_file, $pdf_begin.$fac.$gd.$oc.$res.$pdf_end);
			system("java -jar {$_pd4ml} file:{$_file} {$_down}");
			
			$filename = explode('.', basename($_down));
			
			rename($_down, dirname($_down) . DS . '(' . $filename['0'] . ').'.$filename['1']);
			sleep(1);
			*/
			

			
			$this->set(compact('details', 'cedibles'));

			return;
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
						$doc['store_id'] = (int) array_search((int)$data['_CodigoTienda'], $stores);

					if (!empty($data['_TipoDocumento']))
						$doc['type_id'] = array_search($data['_TipoDocumento'], $types);

					if (!empty($data['_NumeroDocumento']))
						$doc['number'] = (int)$data['_NumeroDocumento'];

					if (!empty($data['_NombreRazonSocial']))
						$doc['company'] = $data['_NombreRazonSocial'];

					if (!empty($data['_RUTCliente']))
						$doc['document'] = $data['_RUTCliente'];

					if (!empty($data['_NroOC']))
						$doc['noc'] = $data['_NroOC'];

					if (!empty($data['_NroGuiaDespacho'])) {
						$doc['ngd'] = $data['_NroGuiaDespacho'];

						$ngd = explode('-', $data['_NroGuiaDespacho']);
						$doc['ngd_0'] = (int)$ngd[0];
						$doc['ngd_1'] = (int)$ngd[1];
					}

					if (!empty($data['_NroPVT'])) {
						$doc['npvt'] = $data['_NroPVT'];

						$npvt = explode('/', $data['_NroPVT']);
						$doc['npvt_0'] = (int)$npvt[0];
						$doc['npvt_1'] = (int)$npvt[1];
						$doc['npvt_2'] = $npvt[2];
					}

					if (!empty($data['_CondicionPago']))
						$doc['payment'] = $data['_CondicionPago'];

					if (!empty($data['_Lote']))
						$doc['lote'] = $data['_Lote'];

					if (!empty($data['_FechaProcesamiento']))
						$doc['processed'] = date('Y-m-d H:i:s', strtotime($data['_FechaProcesamiento']));

					if (!empty($doc['processed']) && !empty($doc['lote'])) {
						$path = WWW_ROOT . 'img' . DS . ($dte ? 'dte' : 'docs') . DS . date('Y' . DS . 'm' . DS . 'd', strtotime($doc['processed'])) . DS . $doc['lote'] . DS . date('His', strtotime($doc['processed']));

						if (is_dir($path)) {
							$images = glob($path . DS . '{*.jpeg,*.jpg}', GLOB_BRACE);

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
			$message = __('Se han importado %n DTE.', $imports);
			if ($matched = $this->match()) {
				$message.= __('Se han conciliado %n DTE', $matched);
				$type = 'match';
			}

			$this->Session->setFlash($message);

			$this->redirect(array('controller' => 'docs', 'action' => 'index', $type));
		}

		public function manual() {
			$type = 'dte';
			if ($matched = $this->match(false)) {
				$this->Session->setFlash(__('Se han conciliado %n DTE', $matched));
				$type = 'match';
			}

			$this->redirect(array('controller' => 'docs', 'action' => 'index', $type));
		}

		public function match($cron = true) {
			$this->autoRender = false;

			// Traigo todo los dte que no tienen documentos asociados
			$this->Doc->virtualFields['cedibles'] = '(SELECT COUNT(id) FROM docs WHERE parent_id = Doc.id AND dte = 0)';

			$conditions = array(
				'Doc.match' => 0,
				'Doc.dte' => 1,
				'Doc.cedibles' => 0
			);

			if (!$cron && empty($this->stores_users_active))
				return __('No se puede realizar match manual');

			if (!Configure::read('debug'))
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

			$matched = 0;
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
					$conditions['match'] = 0; // No debe estar unido a ningún DTE (Cover)
					$conditions['dte'] = 0; // No debe ser DTE (Cover)

					$cedibles = $this->Doc->find(
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

					if (empty($cedibles))
						continue;

					foreach ($cedibles AS $cedible) {
						$this->Doc->id = $cedible['Doc']['id'];

						$cedible_data = array(
							'parent_id' => $row['Doc']['id'],
							'match' => 1
						);

						$this->Doc->save($cedible_data);
					}

					// Se actualiza el DTE como matcheado con sus cedibles
					$this->Doc->id = $row['Doc']['id'];
					$this->Doc->saveField('match', 1);

					$matched++;

					debug(compact('conditions', 'row', 'cedibles'));
				}				
			}

			return $matched;
		}
	}