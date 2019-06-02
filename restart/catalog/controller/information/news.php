<?php

class ControllerInformationNews extends Controller {
	private function date($date) {
		$newDatetime = new Datetime($date);
		$rus_months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		$month = $newDatetime->format('n');
		$album_data = $newDatetime->format('j '.$rus_months[$month-1].'');
		$album_data .= $newDatetime->format(' Y года');
		return $album_data;
	}

	public function index(){
		$this->load->language('information/news');

		$this->load->model('catalog/news');

		$this->load->model('tool/image');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 1;
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->config->get('config_name'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/news', $url)
		);

		$news_years = $this->model_catalog_news->getYears();
		foreach ($news_years as $year) {
			$data['years'][] = array(
				'year' => $year['year'],
				'href' => $this->url->link('information/news', 'year=' . $year['year']),
			);
		}

		if (isset($this->request->get['year'])) {
			$current_year = $this->request->get['year'];
		} else {
			$current_year = $data['years'][0]['year'];
		}

		$data['current_year'] = $current_year;

		$filter_data = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
			'year' => $current_year
		);

		$news_total = $this->model_catalog_news->getTotalNews($filter_data);
		$news_list = $this->model_catalog_news->getNews($filter_data);

		$data['news_list'] = array();

		if ($news_list) {

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_empty'] = $this->language->get('text_empty');

			foreach ($news_list as $result) {

				if($result['image']){
					$image = $this->model_tool_image->resize($result['image'], 200, 200);
				}else{
					$image = false;
				}

				$data['news_list'][] = array(
					'title' => $result['title'],
					'image' => $image,
					'href' => $this->url->link('information/news_info/info', 'news_id=' . $result['news_id']),
					'date_added' => $this->date($result['date_added'])
				);
			}
		}

		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $news_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('information/news', $url . '&page={page}');

		$data['pagination'] = $pagination->custom_render();
		$data['$news_total'] = $news_total;

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('information/news', '', true), 'canonical');
		} elseif ($page == 2) {
			$this->document->addLink($this->url->link('information/news', '', true), 'prev');
		} else {
			$this->document->addLink($this->url->link('information/news', '&page=' . ($page - 1), true), 'prev');
		}

		if ($limit && ceil($news_total / $limit) > $page) {
			$this->document->addLink($this->url->link('information/news', '&page=' . ($page + 1), true), 'next');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/news_list', $data));
	}
}
