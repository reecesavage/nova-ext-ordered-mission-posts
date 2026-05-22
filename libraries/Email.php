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

		$query = $ci->db->get_where('missions', array('mission_id' => $missionId));
		$mission = ($query->num_rows() > 0) ? $query->row() : false;

		if ( ! empty($mission) && $mission->mission_ext_ordered_post_numbering == 1) {
			$count = $ci->db->get_where('posts', array(
				'post_mission' => $missionId,
				'post_status'  => 'activated',
			))->num_rows();

			$data['title'] = 'Post '.$count.' - '.$data['title'];
		}

		return $data;
	}
}
