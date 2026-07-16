# FileDownload — Joomla 3 Template (Bootstrap 4)

A file-download / digital marketplace template matching the supplied
mockup: dark header with search + nav, category sidebar, top-downloads
list, hero search banner, file listing table with ratings & download
buttons, and a 4-column footer with newsletter signup.

## What's included

```
filedownload/
├── templateDetails.xml     Joomla manifest (module positions, params)
├── index.php               Main layout (Bootstrap 4 grid + jdoc includes)
├── component.php           Bare layout for tp=1 / print requests
├── error.php                404 / error page
├── css/template.css         All styling (light + dark mode)
├── js/template.js           Dark-mode toggle, sticky header
├── html/modules.php         Custom module chrome (fd_list, fd_topdownloads…)
├── language/en-GB/…          Language strings
└── demo.html                 Static preview — open directly in a browser,
                               no Joomla install required
```

## Install into Joomla 3

1. Zip the `filedownload` folder (or use the zip already provided).
2. In the Joomla admin: **Extensions → Manage → Install**, upload the zip.
3. **System → Site Templates → Styles**, select **filedownload**, click
   **Make Default** (or assign it per-menu-item).
4. Go to **Extensions → Modules → New** and create modules for each
   position below, assigning the matching **Module Style / Position**:

| Position            | Suggested module type                          | Chrome style   |
|---------------------|--------------------------------------------------|----------------|
| `search`            | Search (mod_search) — optional, header already has a built-in search box | `raw` |
| `menu`               | Menu (mod_menu)                                  | `none`         |
| `sidebar-categories` | Menu or Custom HTML — list of category links     | `fd_list`      |
| `sidebar-top`        | Custom HTML — "Top Downloads" list                | `fd_topdownloads` |
| `sidebar-promo`      | Custom HTML — "Upload Your Files" CTA card        | `fd_promo`     |
| `banner`             | Banners (mod_banners), optional                  | `none`         |
| `breadcrumbs`        | Breadcrumbs (mod_breadcrumbs)                    | `none`         |
| `footer-about`       | Custom HTML — short site description              | `fd_raw`       |
| `footer-links`       | Custom HTML — social icons (fd_social) **and** a separate menu for Quick Links (fd_footerlist) — create two modules in this position | `fd_social` / `fd_footerlist` |
| `footer-categories`  | Menu — category links                             | `fd_footerlist`|
| `footer-support`     | Menu — Help/Terms/Privacy/DMCA links               | `fd_footerlist`|
| `footer-newsletter`  | Custom HTML or a newsletter extension's form       | `fd_newsletter`|

5. The **"Latest Files"** table itself is the Joomla **component** area
   (`jdoc:include type="component"`) — hook up whichever file-download /
   directory component you're using (e.g. a custom `com_filedownload`,
   DOCman, or similar) so its list view outputs into `.fd-listing-card`,
   `.fd-table`, `.fd-btn-download` etc. `demo.html` shows the exact
   markup/classes those views should target.

## Template options

Under **Site Templates → Styles → filedownload → Options** you can set:
- **Primary Color** / **Header-Footer Dark Color** — drives all CSS via
  custom properties, no code edits needed.
- **Site Name** — text shown next to the logo.
- **Show Hero Banner** — toggle the big search hero on the home page.

## Notes

- Bootstrap 4.6.2, Font Awesome 5, and the Inter font are loaded from
  CDN in `index.php` — swap those `addStyleSheet()` / `addScript()`
  calls for local copies under `css/` and `js/` if you need an
  offline/self-hosted build.
- Dark mode is a pure front-end toggle (`js/template.js`), stored in
  `localStorage`; it doesn't depend on any Joomla setting.
- All module positions use custom "chrome" (`html/modules.php`) so
  module output drops straight into the right markup — no manual
  wrapping divs needed inside each module's Custom HTML content.
