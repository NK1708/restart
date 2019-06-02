<?php
class ControllerCatalogServicesFeatured extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/services_featured');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/services_featured');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/services_featured');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/services_featured');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();

			if (isset($this->request->post['service_description']['name'])) {
				$data['service_description']['name'] = $this->request->post['service_description']['name'];
			} else {
				$data['service_description']['name'] = '';
			}

			if (isset($this->request->post['service_description']['less_description'])) {
				$data['service_description']['less_description'] = $this->request->post['service_description']['less_description'];
			} else {
				$data['service_description']['less_description'] = '';
			}

			if (isset($this->request->post['service_description']['description'])) {
				$data['service_description']['description'] = $this->request->post['service_description']['description'];
			} else {
				$data['service_description']['description'] = '';
			}

			if (isset($this->request->post['service_description']['price'])) {
				$data['service_description']['price'] = $this->request->post['service_description']['price'];
			} else {
				$data['service_description']['price'] = '';
			}

			if (isset($this->request->post['service_description']['device_id'])) {
				$data['service_description']['device_id'] = $this->request->post['service_description']['device_id'];
			} else {
				$data['service_description']['device_id'] = '';
			}

			if (isset($this->request->post['service_description']['meta_title'])) {
				$data['service_description']['meta_title'] = $this->request->post['service_description']['meta_title'];
			} else {
				$data['service_description']['meta_title'] = '';
			}

			if (isset($this->request->post['service_description']['meta_description'])) {
				$data['service_description']['meta_description'] = $this->request->post['service_description']['meta_description'];
			} else {
				$data['service_description']['meta_description'] = '';
			}

			if (isset($this->request->post['service_description']['meta_keyword'])) {
				$data['service_description']['meta_keyword'] = $this->request->post['service_description']['meta_keyword'];
			} else {
				$data['service_description']['meta_keyword'] = '';
			}

			if (isset($this->request->post['service_seo_url'])) {
				$data['service_seo_url'] = $this->request->post['service_seo_url'];
			} else {
				$data['service_seo_url'] = '';
			}

      if (isset($this->request->post['services_image'])) {
				$data['services_image'] = $this->request->post['services_image'];
			} else {
				$data['services_image'] = array();
			}

			$this->model_catalog_services_featured->addServices($data);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/services_featured');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/services_featured');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$data = array();

			if (isset($this->request->post['service_description']['name'])) {
				$data['service_description']['name'] = $this->request->post['service_description']['name'];
			} else {
				$data['service_description']['name'] = '';
			}

			if (isset($this->request->post['service_description']['less_description'])) {
				$data['service_description']['less_description'] = $this->request->post['service_description']['less_description'];
			} else {
				$data['service_description']['less_description'] = '';
			}

			if (isset($this->request->post['service_description']['description'])) {
				$data['service_description']['description'] = $this->request->post['service_description']['description'];
			} else {
				$data['service_description']['description'] = '';
			}

			if (isset($this->request->post['service_description']['price'])) {
				$data['service_description']['price'] = $this->request->post['service_description']['price'];
			} else {
				$data['service_description']['price'] = '';
			}

			if (isset($this->request->post['service_description']['meta_title'])) {
				$data['service_description']['meta_title'] = $this->request->post['service_description']['meta_title'];
			} else {
				$data['service_description']['meta_title'] = '';
			}

			if (isset($this->request->post['service_description']['meta_description'])) {
				$data['service_description']['meta_description'] = $this->request->post['service_description']['meta_description'];
			} else {
				$data['service_description']['meta_description'] = '';
			}

			if (isset($this->request->post['service_description']['meta_keyword'])) {
				$data['service_description']['meta_keyword'] = $this->request->post['service_description']['meta_keyword'];
			} else {
				$data['service_description']['meta_keyword'] = '';
			}

			if (isset($this->request->post['service_description']['device_id'])) {
				$data['service_description']['device_id'] = $this->request->post['service_description']['device_id'];
			} else {
				$data['service_description']['device_id'] = '';
			}

			if (isset($this->request->post['service_seo_url'])) {
				$data['service_seo_url'] = $this->request->post['service_seo_url'];
			} else {
				$data['service_seo_url'] = '';
			}

			if (isset($this->request->post['services_image'])) {
				$data['services_image'] = $this->request->post['services_image'];
			} else {
				$data['services_image'] = array();
			}

			$this->model_catalog_services_featured->editServices($this->request->get['services_id'], $data);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/services_featured');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/services_featured');

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $services_id) {
				$this->model_catalog_services_featured->deleteServices($services_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/services_featured/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/services_featured/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['services'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$services_total = $this->model_catalog_services_featured->getTotalServices();

		$results = $this->model_catalog_services_featured->getServicess($filter_data);

		foreach ($results as $result) {
			$data['services'][] = array(
				'services_id' => $result['services_id'],
				'name'        => $result['name'],
				'edit'        => $this->url->link('catalog/services_featured/edit', 'user_token=' . $this->session->data['user_token'] . '&services_id=' . $result['services_id'] . $url, true)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . '&sort=agd.name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $services_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($services_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($services_total - $this->config->get('config_limit_admin'))) ? $services_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $services_total, ceil($services_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/services_featured_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['services_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['services_id'])) {
			$data['action'] = $this->url->link('catalog/services_featured/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/services_featured/edit', 'user_token=' . $this->session->data['user_token'] . '&services_id=' . $this->request->get['services_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/services_featured', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['services_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$services_info = $this->model_catalog_services_featured->getServices($this->request->get['services_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['service_description'])) {
			$data['service_description'] = $this->request->post['service_description'];
		} elseif (isset($this->request->get['services_id'])) {
			$data['service_description'] = $this->model_catalog_services_featured->getServicesDescriptions($this->request->get['services_id']);
		} else {
			$data['service_description'] = array();
		}

		if ($data['service_description'] && $data['service_description']['device_id']) {
			$this->load->model('catalog/category');
			$category = $this->model_catalog_category->getCategory($data['service_description']['device_id']);
			$data['path_device'] = $category['name'];
		} else {
			$data['path_device'] = '';
		}

		if (isset($this->request->post['service_seo_url'])) {
			$data['service_seo_url'] = $this->request->post['service_seo_url'];
		} elseif (isset($this->request->get['services_id'])) {
			$data['service_seo_url'] = $this->model_catalog_services_featured->getServiceSeoUrls($this->request->get['services_id']);
		} else {
			$data['service_seo_url'] = array();
		}

		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

    $this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

    if (isset($this->request->get['services_id'])) {
      $services_images = $this->model_catalog_services_featured->getServicesImages($this->request->get['services_id']);

      $data['services_images'] = array();

      foreach ($services_images as $services_image) {
        if (is_file(DIR_IMAGE . $services_image['image'])) {
          $image = $services_image['image'];
          $thumb = $services_image['image'];
        } else {
          $image = '';
          $thumb = 'no_image.png';
        }

        $data['services_images'][] = array(
          'image'      => $image,
          'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
          'sort_order' => $services_image['sort_order']
        );
      }
    }

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/services_featured_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/services_featured')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['service_description']['name']) < 1) || (utf8_strlen($this->request->post['service_description']['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ($this->request->post['service_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($this->request->post['service_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}

						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['services_id']) || ($seo_url['query'] != 'services_id=' . $this->request->get['services_id']))) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');

								break;
							}
						}
					}
				}
			}
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_category->getCategoryServices($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
