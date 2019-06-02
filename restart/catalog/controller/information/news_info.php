<?php
class ControllerInformationNewsInfo extends Controller {
    private function date($date) {
      $newDatetime = new Datetime($date);
      $rus_months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
      $month = $newDatetime->format('n');
      $album_data = $newDatetime->format('j '.$rus_months[$month-1].'');
      $album_data .= $newDatetime->format(' Y года');
      return $album_data;
    }

    public function info(){
		$this->language->load('information/news');

		$this->load->model('catalog/news');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->config->get('config_name'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('information/news'),
			'text' => $this->language->get('heading_title')
		);

		if (isset($this->request->get['news_id'])) {
			$news_id = $this->request->get['news_id'];
		} else {
			$news_id = 0;
		}

		$news_info = $this->model_catalog_news->getNewsStory($this->request->get['news_id']);

		if ($news_info) {

			if ($news_info['meta_title']) {
				$this->document->setTitle($news_info['meta_title']);
			} else {
				$this->document->setTitle($news_info['title']);
			}

			$this->document->setDescription($news_info['meta_description']);
			$this->document->setKeywords($news_info['meta_keyword']);

			$data['heading_title'] = $news_info['title'];

			$data['breadcrumbs'][] = array(
				'text' => $news_info['title'],
				'href' => $this->url->link('information/news/info', 'news_id=' . $news_id)
			);

			$this->document->addLink($this->url->link('information/news', 'news_id=' . $this->request->get['news_id']),
				'canonical');

			$this->load->model('tool/image');

			if($news_info['image']){
				$data['image'] = $this->model_tool_image->resize($news_info['image'], 1920, 500);
			} else{
				$data['image'] = false;
			}

			if (isset($_SERVER['HTTP_REFERER'])) {
				$data['referred'] = $_SERVER['HTTP_REFERER'];
			}

			$data['description'] = html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8');
      $data['date_added'] = $this->date($news_info['date_added']);

      $data['next_news'] = '';
      $data['prev_next'] = '';

      $next_id = $this->model_catalog_news->getNextNews($news_id);
      if ($next_id) {
        $data['next_news'] = $this->url->link('information/news_info/info', 'news_id=' . $next_id);
      }
      $prev_id = $this->model_catalog_news->getPrevNews($news_id);
      if ($prev_id) {
        $data['prev_news'] = $this->url->link('information/news_info/info', 'news_id=' . $prev_id);
      }

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/news', $data));

		} else {
			$url = '';

			if (isset($this->request->get['news_id'])) {
				$url .= '&news_id=' . $this->request->get['news_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/news/info', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

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
