# Ordered Mission Posts - A Nova 2.6.1 Extension
This extension provides multiple methods for ordering mission posts. Day/Time, Date/Time, Stardate(decimal)/Time.

 

 This extension requires:

- Nova 2.6+
- Nova Extension [`jquery`](https://github.com/jonmatterson/nova-ext-jquery)
- Nova Extension [`timepicker`](https://github.com/jonmatterson/nova-ext-timepicker)

## Installation

Copy the entire directory into `applications/extensions/nova_ext_ordered_mission_posts`.

Run the following command in your MySQL database:

```
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_day INTEGER NOT NULL DEFAULT 1;
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_time VARCHAR(4) NOT NULL DEFAULT '0000';
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_date VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_posts ADD COLUMN nova_ext_ordered_post_stardate VARCHAR(255) DEFAULT NULL;
ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_config_setting VARCHAR(255) DEFAULT NULL;

ALTER TABLE nova_missions ADD COLUMN mission_ext_ordered_post_numbering INTEGER NOT NULL DEFAULT 0;
```

Add the following to `application/config/extensions.php`:

```
$config['extensions']['enabled'][] = 'jquery';
$config['extensions']['enabled'][] = 'timepicker';

$config['extensions']['enabled'][] = 'nova_ext_ordered_mission_posts';


// day_time, date_time,startdate_time
$config['extensions']['nova_ext_ordered_mission_posts']['format']='startdate_time'; 

```
