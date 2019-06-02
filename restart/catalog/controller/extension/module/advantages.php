<?php
class ControllerExtensionModuleAdvantages extends Controller {
	public function index($setting) {
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['title'] = $setting['module_description'][$this->config->get('config_language_id')]['title'];
      $data['description'] = $setting['module_description'][$this->config->get('config_language_id')]['description'];

      $data['title2'] = $setting['module_description'][$this->config->get('config_language_id')]['title2'];
      $data['description2'] = $setting['module_description'][$this->config->get('config_language_id')]['description2'];

      $data['title3'] = $setting['module_description'][$this->config->get('config_language_id')]['title3'];
      $data['description3'] = $setting['module_description'][$this->config->get('config_language_id')]['description3'];

			return $this->load->view('extension/module/advantages', $data);
		}
	}
}
