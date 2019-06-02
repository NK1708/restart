<?php
class ControllerInformationContact extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

		$this->document->addScript("https://api-maps.yandex.ru/2.1/?lang=ru_RU");

		$data['branchs'] = array();

		$this->load->model('catalog/contact');

		$branchs = $this->model_catalog_contact->getBranches();

		if ($branchs) {
			foreach ($branchs as $branch) {
				$data['branchs'][] = array(
					'branch_id'	 	=> $branch['branch_id'],
					'name'        => $branch['name'],
				);
			}
		}

		$data['branch'] = $this->model_catalog_contact->getBranch($branchs[0]['branch_id']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/contact', $data));
	}

	protected function validate() {
		$this->load->language('information/contact');
		if ((utf8_strlen($this->request->post['callback_name']) < 3) || (utf8_strlen($this->request->post['callback_name']) > 32)) {
			$this->error['callback_name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['callback_telephone']) < 10)) {
			$this->error['callback_telephone'] = $this->language->get('error_phone');
		}

		return !$this->error;
	}

  public function callback_modal() {
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
      $mail->setFrom('no-replay@restarekb.ru');
      $mail->setSender(html_entity_decode($this->request->post['callback_name'], ENT_QUOTES, 'UTF-8'));
      $mail->setSubject(html_entity_decode($this->request->post['callback_subject'], ENT_QUOTES, 'UTF-8'));
      $mail->setText($this->request->post['callback_telephone']);
      $mail->send();
    }
		$json['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	protected function validate_request_form() {
		$this->load->language('information/contact');

		if ((utf8_strlen($this->request->post['request_form_phone']) < 10)) {
			$this->error['request_form_phone'] = $this->language->get('error_phone');
		}

		return !$this->error;
	}

  public function request_form() {
		$json = array();
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate_request_form()) {
      $mail = new Mail($this->config->get('config_mail_engine'));
      $mail->parameter = $this->config->get('config_mail_parameter');
      $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
      $mail->smtp_username = $this->config->get('config_mail_smtp_username');
      $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
      $mail->smtp_port = $this->config->get('config_mail_smtp_port');
      $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

      $mail->setTo($this->config->get('config_email'));
      $mail->setFrom('no-replay@restarekb.ru');
      $mail->setSender(html_entity_decode($this->request->post['request_form_phone'], ENT_QUOTES, 'UTF-8'));
      $mail->setSubject(html_entity_decode($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8'));
			$str = $this->request->post['request_form_device'] . ' - ' . $this->request->post['request_form_problem'] . ' Телефон: ' . $this->request->post['request_form_phone'];
      $mail->setText($str);
      $mail->send();
    }

		$json['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function success() {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}

  public function callback() {
		$json = array();
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom('no-replay@restarekb.ru');
		$mail->setSender(html_entity_decode($this->request->post['callback_phone'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8'));
		$mail->setText($this->request->post['callback_phone']);
		$mail->send();

		$json['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }

	public function getBranch() {
		$this->load->model('catalog/contact');

		$json = $this->model_catalog_contact->getBranch($this->request->post['branch_id']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
