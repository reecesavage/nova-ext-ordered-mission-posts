<?php

namespace nova_ext_ordered_mission_posts;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Logic the Write controller's _email shim delegates to.
 *
 * Keeping it here means behaviour can change without re-editing
 * application/controllers/Write.php.
 */
class Email
{
	/**
	 * For mission post emails, prefix the title with "Post N -" when the
	 * mission has post numbering enabled.
	 */
	public static function filter($type, $data)
	{
		if ($type !== 'post') {
			return $data;
		}

		$ci =& get_instance();
		$missionId = $ci->input->post('mission', true);

		if (empty($missionId)) {
			return $data;
		}

		// The post has been inserted by the time _email runs; the most recent
		// activated post for this mission is the one being announced. Look it
		// up by post_id so the chronological number matches the website even
		// when the new post sorts ahead of existing ones.
		$latestQuery = $ci->db
			->select('post_id')
			->where('post_mission', $missionId)
			->where('post_status', 'activated')
			->order_by('post_date', 'desc')
			->limit(1)
			->get('posts');
		$latest = ($latestQuery->num_rows() > 0) ? $latestQuery->row() : false;

		if (empty($latest)) {
			return $data;
		}

		$number = PostNumber::forPost($latest->post_id, $missionId);
		if ($number !== null) {
			$data['title'] = 'Post '.$number.' - '.$data['title'];
		}

		return $data;
	}
}
