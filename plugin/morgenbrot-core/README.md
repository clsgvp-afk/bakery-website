# Morgenbrot Core

Business/data companion for the Morgenbrot portfolio theme. It requires WordPress 6+ and PHP 8+ and does not use WooCommerce, payment services, analytics, external APIs or real email delivery.

## Installation and activation

1. Copy `morgenbrot-core` to `wp-content/plugins/`.
2. Activate **Morgenbrot Core** in Plugins.
3. Activate the **Morgenbrot** theme.
4. Use readable permalinks such as `/%postname%/`.

Rewrite rules are flushed on plugin activation/deactivation and on explicit demo install/removal only, never on ordinary requests.

## Demo installer

Open **Morgenbrot → Demo-Inhalte installieren** as an administrator. The nonce-protected manual action creates/updates nine pages, a menu, 12 products, three clearly fictional locations and three posts. Every record is marked with a stable demo key, so reruns do not duplicate content.

The separate removal action requires a checkbox, browser confirmation, administrator capability and nonce. It deletes only IDs held in the plugin's demo manifest. User-created content is never selected by title or deleted.

## Products

Manage **Sortiment** in WordPress Admin. Native title, excerpt, editor and featured image provide the primary content. The Product data box provides unique SKU, Media Library gallery, numeric regular/sale prices, price unit, weight, ingredients, stock quantity/status and vegan/vegetarian/seasonal/featured flags. Categories and allergens are native WordPress taxonomies. Duplicate SKU values are rejected with an admin notice. Prices are stored as normalized decimals and rendered in German euro format.

## Locations

Manage **Filialen** in WordPress Admin. Add a featured image, clearly fictional address/contact data, latitude/longitude, comma-separated opening intervals for every weekday, temporary closure, parking, accessible entrance and services. Example hours: `06:00-12:00, 13:00-18:00`; an interval such as `22:00-02:00` crosses midnight. Do not enter private residential coordinates.

## Enquiries

Preorders and contact submissions are private post types and are never exposed through REST. Forms use nonces, honeypots, per-IP transient throttling, server-side entity/date validation and sanitization. Preorders store product snapshots, quantities, location and a non-binding total. Status and internal note are editable; CSV export is administrator-only and neutralizes spreadsheet formulas.

## Privacy and maps

Leaflet is bundled locally by the theme. OpenStreetMap tiles are requested only after the visitor presses **Karte laden**. This transmits the visitor IP to OpenStreetMap; keep the consent copy and attribution when customizing the map. No map key or tracking is used.

## Testing

Lint all PHP with the LocalWP PHP binary, check authored JS with `node --check`, activate the plugin, run the installer twice, verify counts/SKU uniqueness, test archive filters, a single product, cart changes/removal, a valid and invalid preorder, contact storage, location hours, privacy-gated map markers and mobile navigation. LocalWP may emit an environment warning if its optional Imagick DLL is missing; this plugin does not require Imagick.
