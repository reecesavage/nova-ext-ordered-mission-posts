# Ordered Mission Posts - A [Nova](https://anodyne-productions.com/nova) Extension

<p align="center">
  <a href="https://github.com/reecesavage/nova-ext-ordered-mission-posts/releases/tag/v1.2.0"><img src="https://img.shields.io/badge/Version-v1.2.0-brightgreen.svg"></a>
  <a href="http://www.anodyne-productions.com/nova"><img src="https://img.shields.io/badge/Nova-v2.7.5+-orange.svg"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-v8.x-blue.svg"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-red.svg"></a>
</p>

This extension provides multiple methods for ordering mission posts. Day/Time, Date/Time, Stardate(decimal)/Time, and Post Numbering.

While this extension is not a fork of [`chronological_mission_posts`](https://github.com/jonmatterson/nova-ext-chronological_mission_posts) it would not have been possible without it and [Jon's](https://github.com/jonmatterson?tab=repositories) other wonderful extensions.

This extension requires:

- Nova 2.7.5+
- Nova Extension [`jquery`](https://github.com/jonmatterson/nova-ext-jquery)
- Nova Extension [`timepicker`](https://github.com/jonmatterson/nova-ext-timepicker)
- Nova Mod [`parser_events`](https://github.com/jonmatterson/nova-mod-parser_events)

## Upgrade Considerations
- If upgrading Nova 2.6+ with this Nove Extension already deployed:
- Remove `$config['extensions']['enabled'][] = 'nova_ext_ordered_mission_posts';` from `application/config/extensions.php` prior to the Nova upgrade.
- After upgrading Nova to 2.7.5+, follow the installation steps below. The database tables still contain your data

## Installation

- Install Required Extensions.
- Copy the entire directory into `applications/extensions/nova_ext_ordered_mission_posts`.
- Add the following to `application/config/extensions.php`: - Be sure the `jquery` and `timepicker` lines appear before `nova_ext_ordered_mission_posts`
```
$config['extensions']['enabled'][] = 'nova_ext_ordered_mission_posts';
```
- If you were using `chronological_mission_posts` remove the `$config['extensions']['enabled'][] = 'chronological_mission_posts';` line from `application/config/extensions.php`

### Setup Using Admin Panel - Preferred

- Navigate to your Admin Control Panel
- Choose Ordered Mission Posts under Manage Extensions
- Create Database Columns by clicking "Create Column" for each column. Once all columns are added the message "All expected columns found in the database" will appear.
- Create Indexes by clicking Create Index. The button will appear when all Database columns are detected. Once indexes are added the message "All expected indexes found in the database." will appear.
- Click Update Controller Information to add the `_email` function to your `application/controllers/write.php` file. This allows Post Numbers to be included in Post emails.

Installation is now complete! If you previously used `chronological_mission_posts` check below on how to enable support.

## Usage

- Create or Edit a mission.
- Choose the Timeline Configuration that best fits your game.
- If using Stardate or Date Time enter a default starting date.
- Enter other values as normal.
- Click submit.

### Timeline Configuration
The extension supports the following Timeline Configurations and will sort the posts chronologically unless Post Numbering is enabled.

- Nova Default
	- This option displays the Default Timeline Field on Posts and sort is by post activation time, which is default Nova behavior.
- Day Time
	- Presents the writer with a Day and Time field on Mission Posts.
	- Posts are sorted by Day and then Time.
	- This is similar funcationality to `chronological_mission_posts`
- Date Time
	- Presents the writer with a Date and Time field on Mission Posts.
	- Posts are sorted by Date and then Time.
	- The Admin can also configure a Default Mission Date that will be seeded into posts to position the date picker.
	- Date format is Year-Month-Day ex: 2399-04-20
- Stardate (decimal)
	- Presents the writer with a Stardate and Time field on Mission Posts.
	- Posts are sorted by Stardate and then Time.
	- The Admin can also configure a Default Mission Stardate that will be seeded into posts to position the date picker.
	- Stardate format is decimal ex: 12345.67

### Post Numbering Support
Many games prefer to order posts by post number. This extension will automatically add the correct post number and sort posts by number rather than chronologically.
- Check Post Numbering on the create or edit mission page.
- Click submit.

### Labels
The majority of the labels for this extension can be modified in the admin control panel to fit the needs of your game.

## chronological_mission_posts Support
This extension requires you to replace `chronological_mission_posts`; however, you can enable support for existing missions if you used it previously.

### Enable Support
- Navigate to your Admin Control Panel
- Choose Ordered Mission Posts under Manage Extensions
- If the extension detects the columns required by `chronological_mission_posts`, you will see the message "chronological_mission_posts database columns detected. You may enable Legacy Mode for existing Missions."
- Checking Legacy Mode and clicking Enable/Disable Legacy Mode will add the option to use the Day/Time values from `chronologocal_mission_posts` on existing missions.

### Usage
- After enabling support, edit an existing mission.
- Set the Timeline Configuration to Day Time
- Check Day Time Legacy Mode
- Click Submit

Your posts will now reference the values from `chronological_mission_posts`

## Issues

If you encounter a bug or have a feature request, please report it on GitHub in the issue tracker here: https://github.com/reecesavage/nova-ext-ordered-mission-posts/issues

## License

Copyright (c) 2023 Reece Savage.

This module is open-source software licensed under the **MIT License**. The full text of the license may be found in the `LICENSE` file.
