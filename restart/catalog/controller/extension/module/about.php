<?php
class ControllerExtensionModuleAbout extends Controller {
	public function index($setting) {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$data['repair'] = $setting['module_description'][$this->config->get('config_language_id')]['repair'];
			$data['clients'] = $setting['module_description'][$this->config->get('config_language_id')]['clients'];
			$data['year'] = $setting['module_description'][$this->config->get('config_language_id')]['year'];
			$data['html'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');

			$data['advantages_title'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-title'];
			$data['advantages_description'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-description'];

			$data['advantages_title2'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-title2'];
			$data['advantages_description2'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-description2'];

			$data['advantages_title3'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-title3'];
			$data['advantages_description3'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-description3'];

			$data['advantages_title4'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-title4'];
			$data['advantages_description4'] =  $setting['module_description'][$this->config->get('config_language_id')]['advantages-description4'];

			$this->load->model('design/banner');
			$this->load->model('tool/image');

			$results = $this->model_design_banner->getBanner($setting['banner_id']);
			foreach ($results as $result) {
				if (is_file(DIR_IMAGE . $result['image'])) {
					$data['banners'][] = array(
						'title' => $result['title'],
						'link'  => $result['link'],
						'image' => $this->model_tool_image->resize($result['image'], 250, 200)
					);
				}
			}

			if ($setting['style'] == 'style_1') {
				return $this->load->view('extension/module/about', $data);
			} else {
				return $this->load->view('extension/module/about2', $data);
			}
		}
	}
}
