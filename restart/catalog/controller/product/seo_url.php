<?php
class ControllerProductSeoUrl extends Controller {
	private function rus2translit($string) {
	    $converter = array(
	        'а' => 'a',   'б' => 'b',   'в' => 'v',
	        'г' => 'g',   'д' => 'd',   'е' => 'e',
	        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
	        'и' => 'i',   'й' => 'y',   'к' => 'k',
	        'л' => 'l',   'м' => 'm',   'н' => 'n',
	        'о' => 'o',   'п' => 'p',   'р' => 'r',
	        'с' => 's',   'т' => 't',   'у' => 'u',
	        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
	        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
	        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
	        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

	        'А' => 'A',   'Б' => 'B',   'В' => 'V',
	        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
	        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
	        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
	        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
	        'О' => 'O',   'П' => 'P',   'Р' => 'R',
	        'С' => 'S',   'Т' => 'T',   'У' => 'U',
	        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
	        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
	        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
	        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
	    );
	    return strtr($string, $converter);
	  }

	  private function str2url($str) {
	      // переводим в транслит
	      $str = $this->rus2translit($str);
	      // в нижний регистр
	      $str = strtolower($str);
	      // заменям все ненужное нам на "-"
	      $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
	      // удаляем начальные и конечные '-'
	      $str = trim($str, "-");
	      return $str;
	  }

	public function index() {
		$this->service();
		$this->service_featured();
		$this->category_description();
	}

	public function service() {
		$res = $this->db->query("SELECT services_id, name FROM " . DB_PREFIX . "services");

		foreach ($res->rows as $item) {
			$seo_url_res = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'services_id=" . $item['services_id'] . "'");
			if (!isset($seo_url_res->row['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'services_id=" . $item['services_id'] . "', keyword = '" . $this->str2url($item['name']) . "'");
			}
		}
	}

	public function service_featured() {
		$res = $this->db->query("SELECT services_id, name FROM " . DB_PREFIX . "services_featured");

		foreach ($res->rows as $item) {
			$seo_url_res = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'services_id=" . $item['services_id'] . "'");
			if (!isset($seo_url_res->row['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'services_featured_id=" . $item['services_id'] . "', keyword = '" . $this->str2url($item['name']) . "'");
			}
		}
	}

	public function category_description() {
		$res = $this->db->query("SELECT category_id, name FROM " . DB_PREFIX . "category_description");

		foreach ($res->rows as $item) {
			$seo_url_res = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'category_id=" . $item['category_id'] . "'");
			if (!isset($seo_url_res->row['keyword'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'category_id=" . $item['category_id'] . "', keyword = '" . $this->str2url($item['name']) . "'");
			}
		}
	}
}
