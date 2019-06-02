<?php
class ModelCatalogServices extends Model {
  public function getServiceFeaturedById($service_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services_featured WHERE services_id = '" . $service_id . "'");

    return $query->row;
  }

	public function getServicesFeaturedByCategory($category_id, $limit = 0) {
    $sql = "SELECT * FROM " . DB_PREFIX ."services_featured WHERE category_services_id = '" . $category_id . "'";

    if ($limit > 0) {
      $sql .= " LIMIT 3";
    }

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getServicesByDevice($category_id) {
		$query = $this->db->query("SELECT s.name as service_name, cs.name as category_name, cs.category_services_id as category_services_id, s.services_id, s.price, s.price_new, s.time FROM " . DB_PREFIX ."services s LEFT JOIN " . DB_PREFIX . "category_services cs ON (s.category_services_id = cs.category_services_id) WHERE device_id = '" . $category_id ."' ORDER BY cs.sort_order");

		return $query->rows;
	}

	public function getServicesRelated($service_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."services_related sr LEFT JOIN " . DB_PREFIX ."services s ON (sr.services_id = s.services_id) WHERE sr.services_id = '" . $service_id ."'");

		return $query->rows;
	}

	public function getServicesImages($service_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."services_image WHERE services_id = '" . $service_id . "'");

		return $query->rows;
	}

	public function getServiceById($service_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "services WHERE services_id = '" . $service_id . "'");

		return $query->row;
	}
}
?>
