<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['menu_name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['menu_name'],
					'category_id' => $category['category_id'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/category' && isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$data['main_category'] = $parts[0];
		} elseif (isset($this->request->get['category_id'])) {
			$data['main_category'] = $this->request->get['category_id'];
		} else {
      $data['main_category'] = '';
    }

		return $this->load->view('common/menu', $data);
	}
}
