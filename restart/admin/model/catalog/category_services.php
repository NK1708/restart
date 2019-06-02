<?php
class ModelCatalogCategoryServices extends Model {
	public function addCategoryServices($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category_services SET name = '" . $this->db->escape($data['category_services_description']['name']) . "', sort_order = '" . $this->db->escape($data['category_services_description']['sort_order']) . "'");
	}

	public function editCategoryServices($category_services_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "category_services SET name = '" . $this->db->escape($data['category_services_description']['name']) . "', sort_order = '" . $this->db->escape($data['category_services_description']['sort_order']) . "' WHERE category_services_id = '" . (int)$category_services_id . "'");
	}

	public function deleteCategoryServices($category_services_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_services WHERE category_services_id = '" . (int)$category_services_id . "'");
	}

	public function getCategoryServices($category_services_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_services WHERE category_services_id = '" . (int)$category_services_id . "'");

		return $query->row;
	}

	public function getCategoryServicess($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "category_services";

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

	public function getCategoryServicesDescriptions($category_services_id) {
		$category_services_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_services WHERE category_services_id = '" . (int)$category_services_id . "'");

		foreach ($query->rows as $result) {
			$category_services_data = array(
				'name' => $result['name'],
				'sort_order' => $result['sort_order']
			);
		}

		return $category_services_data;
	}

	public function getTotalCategoryServices() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_services");

		return $query->row['total'];
	}
}
