# Ordered Mission Posts - A [Nova](https://anodyne-productions.com/nova) Extension

<p align="center">
  <a href="https://github.com/reecesavage/nova-ext-ordered-mission-posts/releases/tag/v1.3.1"><img src="https://img.shields.io/badge/Version-v1.3.1-brightgreen.svg"></a>
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

### Upgrading from a version older than 1.3.0
The controller code injected by older releases of this extension didn't carry version markers. After upgrading the extension files, open the admin Status panel - it will detect the existing `_email()` / `posts()` methods in `application/controllers/Write.php` and `Feed.php` and offer an **Update Email Code** / **Update Feed Code** button to replace them in place with the new shim form. No manual surgery required.

If anything looks off, the fallback is always to replace `application/controllers/Write.php` and `application/controllers/Feed.php` with the stock Nova stubs, then click **Install Email Code** / **Install Feed Code** on the admin page.

### Upgrading Nova
- If upgrading Nova 2.6+ with this Nova Extension already deployed:
- Remove `$config['extensions']['enabled'][] = 'nova_ext_ordered_mission_posts';` from `application/config/extensions.php` prior to the Nova upgrade.
- After upgrading Nova to 2.7.5+, follow the installation steps below. The database tables still contain your data.

## Installation

- Install Required Extensions.
- Copy the entire directory into `application/extensions/nova_ext_ordered_mission_posts`.
- Add the following to `application/config/extensions.php`: - Be sure the `jquery` and `timepicker` lines appear before `nova_ext_ordered_mission_posts`
```
$config['extensions']['enabled'][] = 'nova_ext_ordered_mission_posts';
```
- If you were using `chronological_mission_posts` remove the `$config['extensions']['enabled'][] = 'chronological_mission_posts';` line from `application/config/extensions.php`

### Setup Using Admin Panel - Preferred

- Navigate to your Admin Control Panel.
- Choose **Ordered Mission Posts** under Manage Extensions.
- The **Status** panel at the top shows the live state of database columns, indexes, the post email code, the RSS feed code, and legacy mode availability.
- Click **Set Up Database** to add every missing column and create both indexes in a single click. The button only appears when something is missing; it's safe to re-run.
- Click **Install Email Code** to inject the post-numbering shim into `application/controllers/Write.php` so mission post emails can include the post number.
- Click **Install Feed Code** to inject the enhanced RSS feed shim into `application/controllers/Feed.php` so `/feed/posts` includes timeline information and post numbering.

Installation is complete when the Status panel reads "All present" / "Installed and up to date" across the board. If you previously used `chronological_mission_posts`, see below on how to enable support.

## Usage

- Create or Edit a mission.
- Choose the Timeline Configuration that best fits your game.
- If using Stardate or Date Time enter a default starting date.
- Enter other values as normal.
- Click submit.

### Timeline Configuration
The extension supports the following Timeline Configurations. Posts are sorted chronologically by the configured timeline, regardless of whether Post Numbering is enabled.

- Nova Default
	- This option displays the Default Timeline Field on Posts and sort is by post activation time, which is default Nova behavior.
- Day Time
	- Presents the writer with a Day and Time field on Mission Posts.
	- Posts are sorted by Day and then Time.
	- This is similar functionality to `chronological_mission_posts`.
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
Many games prefer to display posts with a sequential number. With Post Numbering enabled, each post's title is prefixed with its 1-based chronological position (`Post 1`, `Post 2`, ...) on the website, in post notification emails, and in the RSS feed - all using the mission's Timeline Configuration. Adding a post with an earlier in-character timeline shifts the numbers automatically; the new post becomes `Post 1` if it sorts first.

- Check Post Numbering on the create or edit mission page.
- Click submit.

### Labels
The majority of the labels for this extension can be modified in the admin control panel to fit the needs of your game.

## chronological_mission_posts Support
This extension requires you to replace `chronological_mission_posts`; however, you can enable support for existing missions if you used it previously.

### Enable Support
- Navigate to your Admin Control Panel.
- Choose **Ordered Mission Posts** under Manage Extensions.
- If the extension detects the columns required by `chronological_mission_posts`, the Status panel will show **Legacy mode: Available** and a Legacy Mode section will appear at the bottom of the page.
- Check **Legacy mode enabled** and click **Save Legacy Mode**. Existing missions can now reuse the Day/Time values from `chronological_mission_posts` when their Timeline Configuration is set to Day Time.

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
