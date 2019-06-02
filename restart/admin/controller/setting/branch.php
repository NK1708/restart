<?php
class ControllerSettingBranch extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/branch');

		$this->getList();
	}

	public function add() {
		$this->load->language('setting/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/branch');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();

			if (isset($this->request->post['branch_description']['name'])) {
				$data['branch_description']['name'] = $this->request->post['branch_description']['name'];
			} else {
				$data['branch_description']['name'] = '';
			}

			if (isset($this->request->post['branch_description']['address'])) {
				$data['branch_description']['address'] = $this->request->post['branch_description']['address'];
			} else {
				$data['branch_description']['address'] = '';
			}

      if (isset($this->request->post['branch_description']['phone'])) {
				$data['branch_description']['phone'] = $this->request->post['branch_description']['phone'];
			} else {
				$data['branch_description']['phone'] = '';
			}

      if (isset($this->request->post['branch_description']['phone2'])) {
				$data['branch_description']['phone2'] = $this->request->post['branch_description']['phone2'];
			} else {
				$data['branch_description']['phone2'] = '';
			}

      if (isset($this->request->post['branch_description']['email'])) {
				$data['branch_description']['email'] = $this->request->post['branch_description']['email'];
			} else {
				$data['branch_description']['email'] = '';
			}

			if (isset($this->request->post['branch_description']['branch_image'])) {
				$data['branch_description']['branch_image'] = $this->request->post['branch_description']['branch_image'];
			} else {
				$data['branch_description']['branch_image'] = array();
			}

			$this->model_setting_branch->addBranch($data);

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

			$this->response->redirect($this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('setting/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/branch');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$data = array();

			if (isset($this->request->post['branch_description']['name'])) {
				$data['branch_description']['name'] = $this->request->post['branch_description']['name'];
			} else {
				$data['branch_description']['name'] = '';
			}

      if (isset($this->request->post['branch_description']['address'])) {
				$data['branch_description']['address'] = $this->request->post['branch_description']['address'];
			} else {
				$data['branch_description']['address'] = '';
			}

      if (isset($this->request->post['branch_description']['phone'])) {
				$data['branch_description']['phone'] = $this->request->post['branch_description']['phone'];
			} else {
				$data['branch_description']['phone'] = '';
			}

      if (isset($this->request->post['branch_description']['phone2'])) {
				$data['branch_description']['phone2'] = $this->request->post['branch_description']['phone2'];
			} else {
				$data['branch_description']['phone2'] = '';
			}

      if (isset($this->request->post['branch_description']['email'])) {
				$data['branch_description']['email'] = $this->request->post['branch_description']['email'];
			} else {
				$data['branch_description']['email'] = '';
			}

			if (isset($this->request->post['branch_image'])) {
				$data['branch_image'] = $this->request->post['branch_image'];
			} else {
				$data['branch_image'] = array();
			}

			$this->model_setting_branch->editBranch($this->request->get['branch_id'], $data);

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

			$this->response->redirect($this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('setting/branch');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/branch');

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $branch_id) {
				$this->model_setting_branch->deleteBranch($branch_id);
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

			$this->response->redirect($this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url, true));
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
			'href' => $this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('setting/branch/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('setting/branch/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['branch'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$branch_total = $this->model_setting_branch->getTotalBranch();

		$results = $this->model_setting_branch->getBranchs($filter_data);

		foreach ($results as $result) {
			$data['branch'][] = array(
				'branch_id' => $result['branch_id'],
				'name'        => $result['name'],
				'edit'        => $this->url->link('setting/branch/edit', 'user_token=' . $this->session->data['user_token'] . '&branch_id=' . $result['branch_id'] . $url, true)
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

		$data['sort_name'] = $this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . '&sort=agd.name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $branch_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($branch_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($branch_total - $this->config->get('config_limit_admin'))) ? $branch_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $branch_total, ceil($branch_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/branch_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['branch_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['branch_id'])) {
			$data['action'] = $this->url->link('setting/branch/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('setting/branch/edit', 'user_token=' . $this->session->data['user_token'] . '&branch_id=' . $this->request->get['branch_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('setting/branch', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['branch_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$branch_info = $this->model_setting_branch->getBranch($this->request->get['branch_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['branch_description'])) {
			$data['branch_description'] = $this->request->post['branch_description'];
		} elseif (isset($this->request->get['branch_id'])) {
			$data['branch_description'] = $this->model_setting_branch->getBranchDescriptions($this->request->get['branch_id']);
		} else {
			$data['branch_description'] = array();
		}

		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

    if (isset($this->request->get['branch_id'])) {
      $branch_images = $this->model_setting_branch->getBranchImages($this->request->get['branch_id']);

      $data['branch_images'] = array();

  		foreach ($branch_images as $branch_image) {
  			if (is_file(DIR_IMAGE . $branch_image['image'])) {
  				$image = $branch_image['image'];
  				$thumb = $branch_image['image'];
  			} else {
  				$image = '';
  				$thumb = 'no_image.png';
  			}

  			$data['branch_images'][] = array(
  				'image'      => $image,
  				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
  				'sort_order' => $branch_image['sort_order']
  			);
  		}
    }

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/branch_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/branch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['branch_description']['name']) < 1) || (utf8_strlen($this->request->post['branch_description']['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category_branch');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_category_branch->getCategoryBranchs($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_branch_id'],
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

	public function autocomplete_device() {
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

			$results = $this->model_catalog_category->getDevicesBranch($filter_data);

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

  public function autocomplete_related() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('setting/branch');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_setting_branch->getBranchs($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'branch_id' => $result['branch_id'],
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
