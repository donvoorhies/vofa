# ## [1.2.0] - 2026-03-08
#
# ### Added
# - `theme.json` — defines colour palette, typography scale, content width and layout for Gutenberg
# - `editor-style.css` — mirrors frontend typography and layout inside the block editor
# - Block CSS in `style.css` — styles for all core blocks: alignments, image, quote, pullquote, separator, buttons, columns, cover, code, table, gallery
#
# ### Changed
# - `functions.php` — added `align-wide`, `responsive-embeds`, `editor-styles` theme support
# Changelog

All notable changes to this project are documented in this file.

## [1.1.0] - 2026-03-06

### Added
- New templates: `404.php`, `archive.php`, `search.php`, `comments.php`, `searchform.php`.
- Translation template file: `languages/vofa.pot`.
- Release checklist and expanded project docs in `README.md`.

### Changed
- Improved WordPress baseline support in `functions.php` (theme supports, textdomain loading, safer enqueueing).
- Updated template structure and pagination handling.
- Added comments integration in singular/page templates.
- Added lightweight search form styling and mobile adjustments in `style.css`.
- Added accessibility utility styles for `.screen-reader-text`.

### Fixed
- Removed risky script overrides that could cause plugin conflicts.
- Normalized i18n domain usage to `vofa`.

