<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Cron
 *
 * @example $cron_model = $this->model_setting_cron;
 *
 * Can be called from $this->load->model('setting/cron');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Cron extends \Opencart\System\Engine\Model {
	/**
	 * Add Cron
	 *
	 * @param string $code
	 * @param string $description
	 * @param string $cycle
	 * @param string $action
	 * @param bool   $status
	 *
	 * @return int
	 */
	public function addCron(string $code, string $description, string $cycle, string $action, bool $status): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cron` SET `code` = '" . $this->db->escape($code) . "', `description` = '" . $this->db->escape($description) . "', `cycle` = '" . $this->db->escape($cycle) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete Cron
	 *
	 * @param int $cron_id primary key of the cron record
	 *
	 * @return void
	 */
	public function deleteCron(int $cron_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * Delete Cron By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 */
	public function deleteCronByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Cron
	 *
	 * @param int $cron_id primary key of the cron record
	 *
	 * @return void
	 */
	public function editCron(int $cron_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `date_modified` = NOW() WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * Edit Status
	 *
	 * @param int  $cron_id primary key of the cron record
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editStatus(int $cron_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `status` = '" . (bool)$status . "' WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * Get Cron
	 *
	 * @param int $cron_id primary key of the cron record
	 *
	 * @return array<string, mixed> cron record that has cron ID
	 */
	public function getCron(int $cron_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");

		return $query->row;
	}

	/**
	 * Get Cron By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getCronByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Cron(s)
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> cron records
	 */
	public function getCrons(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "cron`";

		$sort_data = [
			'code',
			'cycle',
			'action',
			'status',
			'date_added',
			'date_modified'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `date_added`";
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

	/**
	 * Get Total Cron(s)
	 *
	 * @return int total number of cron records
	 */
	public function getTotalCrons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "cron`");

		return (int)$query->row['total'];
	}
}
