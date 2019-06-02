<?php
class ModelCatalogContact extends Model {
	public function getBranches() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch");

		return $query->rows;
	}

	public function getBranch($branch_id) {
		$branch = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch WHERE branch_id = '" . $branch_id . "'");

		$images_results = $this->db->query("SELECT * FROM " . DB_PREFIX . "branch_image WHERE branch_id = '" . $branch_id ."' ORDER BY sort_order");

		$this->load->model('tool/image');
		$images = array();
		if ($images_results->rows) {
			foreach ($images_results->rows as $image) {
				$images[] = array(
					'image' => $this->model_tool_image->resize2($image['image'], 400, 300)
				);
			}
		}

		$data = array(
			'data' => $branch->row,
			'images' => $images
		);

		return $data;
	}
}
?>
