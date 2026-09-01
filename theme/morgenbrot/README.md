# Morgenbrot Theme

Warm, accessible, mobile-first presentation for the fictional **Bäckerei Morgenbrot** portfolio. Public copy is German; implementation documentation and code comments are English.

## Architecture

The theme owns templates, CSS, local placeholder artwork and progressive enhancement. `morgenbrot-core` owns custom post types, metadata, validation, private enquiries, demo lifecycle and query behavior. Activate both components.

## Pages and navigation

The demo installer creates Startseite, Sortiment, Filialen, Über uns, Aktuelles, Vorbestellen, Kontakt, Impressum and Datenschutz, assigns a static front page/posts page and creates the primary/footer menu. Product/location menu links use their real archives. Header/footer CTAs, cards and breadcrumbs use WordPress-generated URLs.

## Sortiment and media

The archive provides server-rendered search, category, property, availability and price/title sorting with pagination; JavaScript only auto-submits convenient controls. Product cards and single pages always query WordPress. Featured images use the native Media Library; additional gallery images use the Product data box. Without media, a local CSS illustration is rendered with meaningful accessible text.

## Locations and map

The locations archive and single template show current status, full schedule, services and contacts. Leaflet 1.9.4 is stored under `assets/vendor/leaflet` with its license. Before consent, visitors receive a local stylized placeholder and complete HTML location list. **Karte laden** then requests OpenStreetMap tiles, renders accessible markers/popups, fits bounds and connects list actions to markers. Keep the mandatory attribution and privacy notice.

## Preorder and contact

`[morgenbrot_preorder]` renders a server-side cart and enquiry form; quantities can be updated or set to zero to remove an item. `preorder.js` adds instant removal, date limits, loading copy and `aria-live` feedback. `[morgenbrot_contact]` stores local private enquiries and sends no email.

## Accessibility and responsive QA

The theme includes a skip link, semantic landmarks, visible focus, labelled forms, breadcrumbs, aria-live regions, Escape-enabled mobile menu, keyboard map controls, no-JS fallbacks and reduced-motion handling. Verify at 320, 375, 768, 1024 and 1440 px after visual changes.

## Production limitations

This is not a real company or legal template. Demo prices, addresses, coordinates, testimonials and contacts are fictional. There is no payment, checkout, delivery promise, analytics or live email. Production use requires real operational confirmation, retention/privacy policies, anti-spam hardening, legal review and replacement of all demo content.
