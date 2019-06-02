<?php
class ControllerProductServices extends Controller {
	public function index() {
		$this->load->model('catalog/category');
    	$this->load->model('catalog/services');

		$this->load->model('tool/image');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->config->get('config_name'),
			'href' => $this->url->link('common/home')
		);

		$service_id = $this->request->get['services_id'];

		$service_info = $this->model_catalog_services->getServiceById($service_id);

		if ($service_info) {
			if ($service_info['meta_title']) {
				$this->document->setTitle($service_info['meta_title']);
			} else {
				$this->document->setTitle($service_info['name']);
			}

			$this->document->setDescription($service_info['meta_description']);
			$this->document->setKeywords($service_info['meta_keyword']);

			$main_category = $this->model_catalog_category->getMainCategory($service_info['device_id']);

			$data['breadcrumbs'][] = array(
				'text' => $main_category['name'],
				'href' => $this->url->link('product/category', 'path=' . $main_category['category_id'])
			);

			$results = $this->model_catalog_category->getCategories($main_category['category_id']);

			foreach ($results as $result) {
				$data['devices'][] = array(
					'name' => $result['name'],
					'href' => $this->url->link('product/category', 'path=' . $result['category_id'])
				);
			}

			$device = $this->model_catalog_category->getCategory($service_info['device_id']);

			$data['breadcrumbs'][] = array(
				'text' => $device['name'],
				'href' => $this->url->link('product/category', 'path=' . $device['category_id'])
			);

			$data['heading_title'] = $service_info['name'];

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $service_info['name'],
				'href' => $this->url->link('product/services', 'services_id=' . $this->request->get['services_id'])
			);

			$data['less_description'] = html_entity_decode($service_info['less_description'], ENT_QUOTES, 'UTF-8');
			$data['description'] = html_entity_decode($service_info['description'], ENT_QUOTES, 'UTF-8');
			$data['price'] = number_format($service_info['price']) . ' руб.';
			$data['price_new'] = number_format($service_info['price_new']) . ' руб.';
			$data['time'] = $service_info['time'];

			$results_image = $this->model_catalog_services->getServicesImages($service_id);

			foreach ($results_image as $result) {
				$data['images'][] = array(
					// 'popup' => $this->model_tool_image->resize($result['image'], 1000, 1000),
					'thumb' => $this->model_tool_image->resize($result['image'], 300, 300)
				);
			}

			$results_related = $this->model_catalog_services->getServicesRelated($service_id);

			foreach ($results_related as $result) {
				$data['services_related'][] = array(
					'name' => $result['name'],
					'href' => $this->url->link('product/services', 'services_id=' . $result['services_id'])
				);
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/services', $data));
		} else {
			$url = '';

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/services', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
