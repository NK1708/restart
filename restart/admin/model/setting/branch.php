<?php
class ModelSettingBranch extends Model {
	public function addBranch($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "branch SET name = '" . $this->db->escape($data['branch_description']['name']) . "', address = '" . $this->db->escape($data['branch_description']['address']) . "', phone = '" . $this->db->escape($data['branch_description']['phone']) . "', phone2 = '" . $this->db->escape($data['branch_description']['phone2']) . "', email = '" . $this->db->escape($data['branch_description']['email']) . "'");

		$branch_id = $this->db->getLastId();

    if (isset($data['branch_image'])) {
			foreach ($data['branch_image'] as $branch_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "branch_image SET branch_id = '" . (int)$branch_id . "', image = '" . $this->db->escape($branch_image['image']) . "', sort_order = '" . (int)$branch_image['sort_order'] . "'");
			}
		}
	}

	public function editBranch($branch_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "branch SET name = '" . $this->db->escape($data['branch_description']['name']) . "', address = '" . $this->db->escape($data['branch_description']['address']) . "', phone = '" . $this->db->escape($data['branch_description']['phone']) . "', phone2 = '" . $this->db->escape($data['branch_description']['phone2']) . "', email = '" . $this->db->escape($data['branch_description']['email']) . "' WHERE branch_id = '" . $branch_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "branch_image WHERE branch_id = '" . (int)$branch_id . "'");

		if (isset($data['branch_image'])) {
			foreach ($data['branch_image'] as $branch_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "branch_image SET branch_id = '" . (int)$branch_id . "', image = '" . $this->db->escape($branch_image['image']) . "', sort_order = '" . (int)$branch_image['sort_order'] . "'");
			}
		}
	}

	public function getBranchRelated($branch_id) {
		$branch_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch_related WHERE branch_id = '" . (int)$branch_id . "'");

		foreach ($query->rows as $result) {
			$branch_related_data[] = $result['related_id'];
		}

		return $branch_related_data;
	}

  public function getBranchImages($branch_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch_image WHERE branch_id = '" . (int)$branch_id . "' ORDER BY sort_order ASC");

		return $query->rows;
  }

	public function deleteBranch($branch_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "branch WHERE branch_id = '" . (int)$branch_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "branch_image WHERE branch_id = '" . (int)$branch_id . "'");
	}

	public function getBranch($branch_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch WHERE branch_id = '" . (int)$branch_id . "'");

		return $query->row;
	}

	public function getBranchs($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "branch";

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

	public function getBranchDescriptions($branch_id) {
		$_branch_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch WHERE branch_id = '" . (int)$branch_id . "'");

		foreach ($query->rows as $result) {
			$_branch_data = array(
				'name' => $result['name'],
				'address' => $result['address'],
				'phone' => $result['phone'],
        'phone2' => $result['phone2'],
				'email' => $result['email'],
			);
		}

		return $_branch_data;
	}

	public function getTotalBranch() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "branch");

		return $query->row['total'];
	}

	public function getBrancheoUrls($branch_id) {
		$category_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'branch_id=" . (int)$branch_id . "'");

		foreach ($query->rows as $result) {
			$category_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $category_seo_url_data;
	}
}
