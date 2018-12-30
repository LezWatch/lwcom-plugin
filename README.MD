## LezWatch Commercials Plugin

Custom plugin for LezWatch Commercials

## Description

The LezWatch Commercials plugin contains custom code for use with the site. Instead of having everything hardcoded into the theme, or shoved in a shared MU Plugin, it was moved to it's own plugin. This allows it to be updated outside of the theme and deploy new features as needed.

## Features

* CMB2 support for CPTs and Taxonomies
* Custom Post Type: Commercials
* Custom Taxonomies: Focuses, Company, Country of Origin

### Plugin Addons

Stored in `/plugins/`

* FacetWP: CMB2 Integration, filter Data before it's saved to rename values (On -> yes for lezpolitation), JS for FacetWP Pagination

## Deployment

Pushes to the `master` branch are automatically deployed via Codeship to:

* lezwatchcommercials.com
