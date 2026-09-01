# Architecture

Morgenbrot separates presentation and business logic into two installable WordPress components.

## Theme

The theme owns page templates, reusable product and location cards, responsive layout, navigation, galleries and the visual system. It consumes WordPress data instead of hard-coding catalogue records into templates.

## Core plugin

`morgenbrot-core` registers domain content, form behaviour, promotions, admin settings and supporting roles. Keeping this functionality outside the theme means a visual redesign does not remove the underlying bakery data or workflows.

## Main flows

1. Editors manage products, locations, promotions and homepage content in WordPress Admin.
2. Archive and single templates render structured public views.
3. JavaScript enhances filtering, maps, navigation, galleries and pre-order interactions.
4. Server-side handlers remain responsible for validation and WordPress data operations.
