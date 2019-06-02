<?php
class ControllerExtensionModuleCallback extends Controller {
	private $error = array();

	public function index() {
    $data = array();
		return $this->load->view('extension/module/callback', $data);
	}

	public function send() {
		$json = array();

		if ($this->validate()) {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->request->post['message_name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['message_name']), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->request->post['callback_phone']);
			$mail->send();
		}

		$json['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		$this->load->language('information/contact');

		if ((utf8_strlen($this->request->post['callback_phone']) < 3) || (utf8_strlen($this->request->post['callback_phone']) > 32)) {
			$this->error['callback_phone'] = $this->language->get('error_phone');
		}

		return !$this->error;
	}
}
