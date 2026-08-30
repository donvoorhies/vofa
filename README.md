# Vofa

Lightweight classic WordPress theme focused on speed, clarity, and minimal setup.

## Highlights

- Lean template structure with classic PHP templates.
- Core theme supports: title tag, post thumbnails, custom logo, HTML5, feed links.
- Navigation areas: primary + footer menus.
- Widget areas: homepage + sidebar.
- Search, archive, comments, and 404 templates included.
- Translation ready via `vofa` text domain and `languages/vofa.pot`.

## Installation

1. Copy the theme folder to `wp-content/themes/vofa`.
2. Activate **Vofa** in WordPress admin under **Appearance → Themes**.
3. (Optional) Assign menus under **Appearance → Menus**.
4. (Optional) Add widgets to homepage/sidebar under **Appearance → Widgets**.

## Template Coverage

- `index.php`
- `front-page.php`
- `page.php`
- `single.php`
- `archive.php`
- `search.php`
- `404.php`
- `comments.php`
- `searchform.php`
 
## Recent changes — August 2026
- Publication dates added to News posts 

## Recent Changes (March 2026)

- Added missing WordPress baseline hooks and supports.
- Normalized i18n setup (`vofa`) and added translation template.
- Fixed template structure and added pagination where relevant.
- Added comments integration for singular templates.
- Added lightweight search form + responsive styling.
- Improved accessibility with `.screen-reader-text` styles.
- Removed risky script overrides for better plugin compatibility.

## Release Checklist

- Verify on latest stable WordPress and one prior major version.
- Verify on PHP 8.1+ (and your current hosting target version).
- Activate theme on a clean install and check front page, single post, page, archive, search, and 404.
- Confirm menus are assigned for both `main-menu` and `footer-menu`.
- Confirm widgets render in homepage and sidebar areas.
- Confirm comments open/render correctly and threaded replies work.
- Confirm search form works on desktop and mobile breakpoints.
- Run with at least one common plugin set (SEO/cache/form) to confirm no JS/PHP conflicts.
- Rebuild/update translations from `languages/vofa.pot` if strings changed.

## License

GPL-2.0-or-later

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.
