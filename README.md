# Morgenbrot – custom WordPress bakery platform

Morgenbrot is an independent portfolio project for a fictional German family bakery. The repository contains a custom WordPress theme and a dedicated functionality plugin rather than a page-builder export.

## What the project demonstrates

- custom WordPress theme with reusable template parts
- dedicated plugin for business logic and content modelling
- custom post types for products and bakery locations
- product catalogue with search, categories and filters
- product detail pages with availability and allergen information
- branch directory with interactive maps
- pre-order workflow and form handling
- configurable promotions and homepage content
- administrative settings and custom user roles
- responsive navigation, galleries and accessible components

## Technology

PHP, WordPress, JavaScript, HTML5, CSS3, Leaflet and Git.

## Repository structure

```text
theme/morgenbrot/       Custom presentation layer
plugin/morgenbrot-core/ Business logic and WordPress integration
docs/                   Architecture notes and QA checklist
```

## Installation

1. Copy `theme/morgenbrot` to `wp-content/themes/`.
2. Copy `plugin/morgenbrot-core` to `wp-content/plugins/`.
3. Activate the **Morgenbrot** theme and **Morgenbrot Core** plugin.
4. Configure the generated product and location content in WordPress Admin.
5. Review permalinks once to refresh rewrite rules.

## Live demo

[Open the static portfolio demo](https://vercel-portfolio-upload.vercel.app/morgenbrot-full/)

## Project status

This is a practice project. Bäckerei Morgenbrot is fictional and no real orders or customer data are processed by the public demo.
