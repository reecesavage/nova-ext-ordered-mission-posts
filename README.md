# Ordered Mission Posts - A [Nova](https://anodyne-productions.com/nova) Extension

This extension provides multiple methods for ordering mission posts. Day/Time, Date/Time, Stardate(decimal)/Time.

While this extension is not a fork of [`chronological_mission_posts`](https://github.com/jonmatterson/nova-ext-chronological_mission_posts) it would not have been possible without it and [Jon's](https://github.com/jonmatterson?tab=repositories) other wonderful extensions.

This extension requires:

- Nova 2.6+
- Nova Extension [`jquery`](https://github.com/jonmatterson/nova-ext-jquery)
- Nova Extension [`timepicker`](https://github.com/jonmatterson/nova-ext-timepicker)
- Nova Mod [`parser_events`](https://github.com/jonmatterson/nova-mod-parser_events)

## Installation

Copy the entire directory into `applications/extensions/nova_ext_ordered_mission_posts`.

Run the following commands on your MySQL database:

```
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_day INTEGER NOT NULL DEFAULT 1;
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_time VARCHAR(4) NOT NULL DEFAULT '0000';
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_date VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_stardate VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_config_setting VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_post_numbering INTEGER NOT NULL DEFAULT 0;
ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_default_mission_date VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_default_stardate VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_legacy_mode INTEGER NOT NULL DEFAULT 0;
```

Add the following to `application/config/extensions.php`:

```
$config['extensions']['enabled'][] = 'jquery';
$config['extensions']['enabled'][] = 'timepicker';
$config['extensions']['enabled'][] = 'nova_ext_ordered_mission_posts';
```

Add the following function in your `applications/controllers/write.php` file to overwrite `_email` function. This will allow the email subject to include Post numbers before the Post title. 

```
protected function _email($type, $data)
	{   
		switch ($type)
		{
			case 'post':  
              if(($id = $this->input->post('mission', true)) !== false)
                {  
                	
              		 $query = $this->db->get_where('missions', array('mission_id' => $id));
   						$model = ($query->num_rows() > 0) ? $query->row() : false;
  						 if(!empty($model) && $model->mission_ext_ordered_post_numbering==1)
   							{
                             
               $queryCount = $this->db->get_where('posts', array('post_mission' => $id,'post_status'=>'activated'));
               $count = $queryCount->num_rows();
                    $title=$data['title'];
                   $data['title']="Post $count - $title";
   							}
                   }
                  parent::_email($type, $data);
			break;
			default:
                    parent::_email($type, $data);
			break;
				
		}
		
	}
```

## Issues

If you encounter a bug or have a feature request, please report it on GitHub in the issue tracker here: https://github.com/reecesavage/nova-ext-ordered-mission-posts/issues

## License

Copyright (c) 2021 Reece Savage.

This module is open-source software licensed under the **MIT License**. The full text of the license may be found in the `LICENSE` file.
