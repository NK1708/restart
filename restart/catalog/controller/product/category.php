<?php
class ControllerProductCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');
    $this->load->model('catalog/services');

		$this->load->model('tool/image');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->config->get('config_name'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {

			$url = '';

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);
			$main_category = $parts[0];

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_main = $this->model_catalog_category->getCategory($path_id);

				if ($category_main) {
					$data['breadcrumbs'][] = array(
						'text' => $category_main['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}
		$results = $this->model_catalog_category->getCategories($main_category);
		foreach ($results as $result) {
			$data['devices'][] = array(
				'name' => $result['name'],
				'href' => $this->url->link('product/category', 'path=' . $main_category . '_' . $result['category_id'] . $url)
			);
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if (($category_info && $category_info['parent_id'] == 0) || ($category_info && isset($category_main))) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize2($category_info['image'], 500, 500);
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

			$data['bottom_text'] = html_entity_decode($category_info['bottom_text'], ENT_QUOTES, 'UTF-8');

			$url = '';

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$image = $this->model_tool_image->resize($result['image'], 150, 200);

				$data['categories'][] = array(
					'name' => $result['name'],
					'image' => $image,
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}

			$result_services_featured = array();

			if ($category_info['parent_id'] == 0) {
				$result_services_featured = $this->model_catalog_services->getServicesFeaturedByCategory($category_id);
			} else {
				$result_services_featured = $this->model_catalog_services->getServicesFeaturedByCategory($category_info['parent_id']);

				$results_services = $this->model_catalog_services->getServicesByDevice($category_id);

				if ($results_services) {
					foreach ($results_services as $result) {
						$services[] = array(
							'service_name' => $result['service_name'],
							'category_name' => $result['category_name'],
							'services_id'	=> $result['services_id'],
							'category_services_id' => $result['category_services_id'],
							'time' => $result['time'],
							'price' => number_format($result['price']) . ' руб.',
							'price_new' => number_format($result['price_new']) . ' руб.'
						);
					}

					$category_name = '';
					foreach ($services as $service) {
						if ($category_name == $service['category_name']) {
							$data['services'][$category_name][] = array(
								'service_name' => $service['service_name'],
								'category_services_id' => $service['category_services_id'],
								'href' => $this->url->link('product/services', 'category_id=' . $main_category . '&services_id=' . $service['services_id']),
								'time' => $service['time'],
								'price' => $service['price'],
								'price_new' => $service['price_new']
							);
						} else {
							$data['services'][$service['category_name']][] = array(
								'service_name' => $service['service_name'],
								'category_services_id' => $service['category_services_id'],
								'href' => $this->url->link('product/services', 'category_id=' . $main_category . '&services_id=' . $service['services_id']),
								'time' => $service['time'],
								'price' => $service['price'],
								'price_new' => $service['price_new']
							);
						}
						$category_name = $service['category_name'];
					}
				}
			}

			foreach ($result_services_featured as $result) {
				$data['services_featured'][] = array(
					'name' => $result['name'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'href' => $this->url->link('product/services_featured', 'featured_services_id=' . $result['services_id'])
				);
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/category', $data));
		} else {
			$url = '';

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

  public function getDevice() {
		$this->load->model('catalog/services');

    $category_id = $this->request->post['category_id'];

    $json['services'] = array();

    $results_services = $this->model_catalog_services->getServicesByDevice($category_id);

    foreach ($results_services as $service) {
      $json['services'][] = array(
        'service_name' => $service['service_name'],
      );
    }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function getDeviceCategory() {
		$this->load->model('catalog/category');
		$data['devices'] = array();

    $category_id = $this->request->post['category_id'];

    $json['devices'] = array();

		$categories = $this->model_catalog_category->getCategories($category_id);

		foreach ($categories as $category) {
			$json['devices'][] = array(
				'name'     => $category['name'],
				'href'     => $this->url->link('product/category', 'path=' . $category_id . '_' . $category['category_id'])
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	protected function validate() {
		$this->load->language('information/contact');
		if ((utf8_strlen($this->request->post['device']) < 3) || (utf8_strlen($this->request->post['device']) > 32)) {
			$this->error['no_model_device'] = $this->language->get('error_device');
		}

		if ((utf8_strlen($this->request->post['phone']) < 10)) {
			$this->error['no_model_phone'] = $this->language->get('error_phone');
		}

		return !$this->error;
	}

  public function callback_no_model() {
		$json = array();
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $mail = new Mail($this->config->get('config_mail_engine'));
      $mail->parameter = $this->config->get('config_mail_parameter');
      $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
      $mail->smtp_username = $this->config->get('config_mail_smtp_username');
      $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
      $mail->smtp_port = $this->config->get('config_mail_smtp_port');
      $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

      $mail->setTo($this->config->get('config_email'));
      $mail->setFrom($this->config->get('config_email'));
      $mail->setSender(html_entity_decode($this->request->post['phone'], ENT_QUOTES, 'UTF-8'));
      $mail->setSubject(html_entity_decode($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8'));
			$str = $this->request->post['device'] . ' Телефон: ' . $this->request->post['phone'];
      $mail->setText($str);
      $mail->send();
    }
		$json['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }
}
