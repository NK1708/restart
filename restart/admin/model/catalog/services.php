<?php
class ModelCatalogServices extends Model {
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
	public function addServices($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "services SET name = '" . $this->db->escape($data['service_description']['name']) . "', less_description = '" . $this->db->escape($data['service_description']['less_description']) . "', description = '" . $this->db->escape($data['service_description']['description']) . "', price = '" . $this->db->escape($data['service_description']['price']) . "', price_new = '" . $this->db->escape($data['service_description']['price_new']) . "', time = '" . $this->db->escape($data['service_description']['time']) . "', category_services_id = '" . $this->db->escape($data['service_description']['parent_id']) . "', meta_title = '" . $this->db->escape($data['service_description']['meta_title']) . "', meta_description = '" . $this->db->escape($data['service_description']['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['service_description']['meta_keyword']) . "', device_id = '" . $this->db->escape($data['service_description']['device_id']) . "'");

		$services_id = $this->db->getLastId();

    if (isset($data['services_image'])) {
			foreach ($data['services_image'] as $services_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "services_image SET services_id = '" . (int)$services_id . "', image = '" . $this->db->escape($services_image['image']) . "', sort_order = '" . (int)$services_image['sort_order'] . "'");
			}
		}

		if (isset($data['service_seo_url']) && array_shift($data['service_seo_url'][0]) != '') {

			foreach ($data['service_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'services_id=" . (int)$services_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'services_id=" . (int)$services_id . "', keyword = '" . $this->str2url($this->db->escape($data['service_description']['name'])) . "'");
		}

		if (isset($data['services_related'])) {
			foreach ($data['services_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "services_related WHERE services_id = '" . (int)$services_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "services_related SET services_id = '" . (int)$services_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "services_related WHERE services_id = '" . (int)$related_id . "' AND related_id = '" . (int)$services_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "services_related SET services_id = '" . (int)$related_id . "', related_id = '" . (int)$services_id . "'");
			}
		}
	}

	public function editServices($services_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "services SET name = '" . $this->db->escape($data['service_description']['name']) . "', less_description = '" . $this->db->escape($data['service_description']['less_description']) . "', description = '" . $this->db->escape($data['service_description']['description']) . "', price = '" . $this->db->escape($data['service_description']['price']) . "', price_new = '" . $this->db->escape($data['service_description']['price_new']) . "', time = '" . $this->db->escape($data['service_description']['time']) . "', category_services_id = '" . $this->db->escape($data['service_description']['parent_id']) . "', meta_title = '" . $this->db->escape($data['service_description']['meta_title']) . "', meta_description = '" . $this->db->escape($data['service_description']['meta_description']) . "', meta_keyword = '" . $this->db->escape($data['service_description']['meta_keyword']) . "', device_id = '" . $this->db->escape($data['service_description']['device_id']) . "' WHERE services_id = '" . $services_id . "'");

    $this->db->query("DELETE FROM " . DB_PREFIX . "services_image WHERE services_id = '" . (int)$services_id . "'");

		if (isset($data['services_image'])) {
			foreach ($data['services_image'] as $services_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "services_image SET services_id = '" . (int)$services_id . "', image = '" . $this->db->escape($services_image['image']) . "', sort_order = '" . (int)$services_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'services_id=" . (int)$services_id . "'");

		if (isset($data['service_seo_url']) && array_shift($data['service_seo_url'][0]) != '') {

			foreach ($data['service_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'services_id=" . (int)$services_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '0', language_id = '1', query = 'services_id=" . (int)$services_id . "', keyword = '" . $this->str2url($this->db->escape($data['service_description']['name'])) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "services_related WHERE services_id = '" . (int)$services_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "services_related WHERE related_id = '" . (int)$services_id . "'");

		if (isset($data['services_related'])) {
			foreach ($data['services_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "services_related WHERE services_id = '" . (int)$services_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "services_related SET services_id = '" . (int)$services_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "services_related WHERE services_id = '" . (int)$related_id . "' AND related_id = '" . (int)$services_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "services_related SET services_id = '" . (int)$related_id . "', related_id = '" . (int)$services_id . "'");
			}
		}
	}

	public function getServicesRelated($services_id) {
		$services_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services_related WHERE services_id = '" . (int)$services_id . "'");

		foreach ($query->rows as $result) {
			$services_related_data[] = $result['related_id'];
		}

		return $services_related_data;
	}

  public function getServicesImages($services_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services_image WHERE services_id = '" . (int)$services_id . "' ORDER BY sort_order ASC");

		return $query->rows;
  }

	public function deleteServices($services_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "services WHERE services_id = '" . (int)$services_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'services_id=" . (int)$services_id . "'");
    $this->db->query("DELETE FROM " . DB_PREFIX . "services_image WHERE services_id = '" . (int)$services_id . "'");
	}

	public function getServices($services_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services WHERE services_id = '" . (int)$services_id . "'");

		return $query->row;
	}

	public function getServicess($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "services";

		$sort_data = array(
			'name',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getServicesDescriptions($services_id) {
		$_services_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services WHERE services_id = '" . (int)$services_id . "'");

		foreach ($query->rows as $result) {
			$_services_data = array(
				'name' => $result['name'],
				'less_description' => $result['less_description'],
				'description' => $result['description'],
				'price' => $result['price'],
				'price_new' => $result['price_new'],
				'time' => $result['time'],
				'category_services_id' => $result['category_services_id'],
				'meta_title' => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword' => $result['meta_keyword'],
				'parent_id'	=> $result['category_services_id'],
				'device_id' => $result['device_id']
			);
		}

		return $_services_data;
	}

	public function getTotalServices() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "services");

		return $query->row['total'];
	}

	public function getServiceSeoUrls($services_id) {
		$category_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'services_id=" . (int)$services_id . "'");

		foreach ($query->rows as $result) {
			$category_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $category_seo_url_data;
	}
}
