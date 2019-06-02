<?php
class ModelCatalogSearch extends Model {
  public function Search($data = array()) {
    $sql = "SELECT 'device' as `type`, cd.category_id as id, cd.name, cd.description FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON (cd.category_id = c.category_id) WHERE c.parent_id != 0 AND cd.name LIKE '%" . $data['filter_name'] . "%' OR cd.description LIKE '%" . $data['filter_name'] . "%'";

		$sql .= " UNION ";

		$sql .= "SELECT 'news' as `type`, news_id as id, title as name, description FROM " . DB_PREFIX . "news_description WHERE title LIKE '%" . $data['filter_name'] . "%' OR description LIKE '%" . $data['filter_name'] . "%'";

		$sql .= " UNION ";

		$sql .= "SELECT 'services' as `type`, services_id as id, name, description FROM " . DB_PREFIX . "services WHERE name LIKE '%" . $data['filter_name'] . "%'";

		$sql .= " UNION ";

		$sql .= "SELECT 'services_featured' as `type`, services_id as id, name, description FROM " . DB_PREFIX . "services_featured WHERE name LIKE '%" . $data['filter_name'] . "%' OR description LIKE '%" . $data['filter_name'] . "%'";

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

  public function getTotalSearch($data = array()) {
    $sql = "SELECT item.id, count(*) FROM (";
    $sql .= "SELECT 'device' as `type`, cd.category_id as id, cd.name, cd.description FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON (cd.category_id = c.category_id) WHERE c.parent_id != 0 AND cd.name LIKE '%" . $data['filter_name'] . "%' OR cd.description LIKE '%" . $data['filter_name'] . "%'";

		$sql .= " UNION ";

		$sql .= "SELECT 'news' as `type`, news_id as id, title as name, description FROM " . DB_PREFIX . "news_description WHERE title LIKE '%" . $data['filter_name'] . "%' OR description LIKE '%" . $data['filter_name'] . "%'";

		$sql .= " UNION ";

		$sql .= "SELECT 'services' as `type`, services_id as id, name, description FROM " . DB_PREFIX . "services WHERE name LIKE '%" . $data['filter_name'] . "%'";

		$sql .= " UNION ";

		$sql .= "SELECT 'services_featured' as `type`, services_id as id, name, description FROM " . DB_PREFIX . "services_featured WHERE name LIKE '%" . $data['filter_name'] . "%' OR description LIKE '%" . $data['filter_name'] . "%')";

    $sql .= " as item GROUP BY id ORDER BY id";

		$query = $this->db->query($sql);

		return $query->num_rows;
  }
}
