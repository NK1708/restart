<?php
class ControllerExtensionModuleRequestForm extends Controller {
	public function index() {

		$this->load->model('catalog/category');
		$data['categories'] = array();
		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			// Level 2
			$children_data = array();
			$children = $this->model_catalog_category->getCategories($category['category_id']);
			foreach ($children as $child) {
				$children_data[] = array(
					'name'  => $child['name'],
          'category_id' => $child['category_id'],
				);
			}

			// Level 1
			$data['categories'][] = array(
				'name'     => $category['name'],
        'category_id' => $category['category_id'],
				'children' => $children_data,
			);
		}
		return $this->load->view('extension/module/request_form', $data);
	}
}
