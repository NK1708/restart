<?php

class ModelCatalogNews extends Model {

  public function getYears() {
    $query = $this->db->query("SELECT YEAR(date_added) as year FROM " . DB_PREFIX ."news GROUP BY YEAR(date_added) ORDER BY YEAR(date_added) DESC");
    return $query->rows;
  }

	public function updateViewed($news_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET viewed = (viewed + 1) WHERE news_id = '" . (int)$news_id . "'");
	}

	public function getNewsStory($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND n.status = '1'");

		return $query->row;
	}

  public function getNextNews($news_id) {
    $query = $this->db->query("SELECT news_id FROM " . DB_PREFIX ."news WHERE news_id > '" . $news_id . "' LIMIT 1");
    if (isset($query->row['news_id'])) {
      return $query->row['news_id'];
    }
  }

  public function getPrevNews($news_id) {
    $query = $this->db->query("SELECT news_id FROM " . DB_PREFIX ."news WHERE news_id < '" . $news_id . "' LIMIT 1");
    if (isset($query->row['news_id'])) {
      return $query->row['news_id'];
    }
  }

	public function getNews($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.status = '1' AND YEAR(n.date_added) = '" . $data['year'] . "'";

		$sort_data = array(
			'nd.title',
			'n.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY n.date_added";
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
				$data['limit'] = 10;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalNews($data) {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news WHERE status = '1' AND YEAR(date_added) = '" . $data['year'] . "'");

		if ($query->row) {
			return $query->row['total'];
		} else {
			return FALSE;
		}
	}
}
?>
