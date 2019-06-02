<?php
class ControllerExtensionModuleServicesFeatured extends Controller {
	public function index() {
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		$data['categories'] = array();
		$categories = $this->model_catalog_category->getCategories(0);
		foreach ($categories as $category) {
			if ($category['top']) {
				$image = $this->model_tool_image->resize2($category['image'], 400, 480);
				$data['categories'][] = array(
					'name'     => $category['name'],
					'image'		 => $image,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		return $this->load->view('extension/module/services_featured', $data);
	}
}
