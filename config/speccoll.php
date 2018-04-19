<?php

$config['skylight_appname'] = 'speccoll';

// Uncomment this if you are using a url of the form http://.../art/...
$config['skylight_url_prefix'] = 'speccoll';

$config['skylight_theme'] = 'speccoll';

$config['skylight_fullname'] = 'Special Collections';

// set ga code
if (strpos($_SERVER['HTTP_HOST'], "test") !== false) {
    $config['skylight_ga_code'] = '';
    $config['skylight_container_id'] = '69';
}
else {
    $config['skylight_ga_code'] = 'UA-25737241-9';
    $config['skylight_container_id'] = '69';
}

$config['skylight_adminemail'] = 'lddt@mlist.is.ed.ac.uk';

$config['skylight_oaipmhcollection'] = 'hdl_10683_14558';

$config['skylight_oaipmhallowed'] = true;

// Container ID and the field used in solr index to store this ID. Used for restricting search/browse scope.

$config['skylight_container_field'] = 'location.coll';
$config['skylight_sitemap_type'] = 'internal';

$config['skylight_fields'] = array(
    'Title' => 'dc.title.en',
    'Author' => 'dc.contributor.author.en',
    'Shelfmark' => 'dc.identifier.en',
    'Date' => 'dc.date.created.en',
    'Type' => 'dc.type.en',
    'Collection' => 'dc.relation.ispartof.en',
    'Bitstream'=> 'dc.format.original.en',
    'ImageURI'=> 'dc.identifier.imageUri.en',
    'Images'=>'dc.format.extent.en'
);

$config['skylight_recorddisplay'] = array(
    'Title',
    'Author',
    'Shelfmark',
    'Date',
    'Collection');

$config['skylight_date_filters'] = array();
$config['skylight_filters'] = array('Author' => 'author_filter', 'Type' => 'type_filter', 'Collection'=> 'collection_filter');
$config['skylight_filter_delimiter'] = ':';

$config['skylight_meta_fields'] = array(
    'Title',
    'Author',
    'Shelfmark',
    'Date');

$config['skylight_searchresult_display'] = array('Title',
    'Author',
    'Shelfmark',
    'Date',
    'Bitstream');

$config['skylight_search_fields'] = array(
    'Title',
    'Author',
    'Shelfmark',
    'Date',

);

$config['skylight_related_fields'] = array('Author' => 'dc.contributor.author.en');

//only by title, no date at the moment
$config['skylight_sort_fields'] = array(
    'Maker' => 'dc.contributor.author_sort ', 'Title' => 'dc.title_sort'
);
$config['skylight_default_sort'] = 'dc.title_sort+asc';

$config['skylight_feed_fields'] = array('Title' => 'Title',
    'Author' => 'Author',
    'Maker' => 'Maker',
    'Shelfmark' => 'Shelfmark',
    'Date' => 'Date');

$config['skylight_related_number'] = 6;
$config['skylight_results_per_page'] = 20;
$config['skylight_show_facets'] = false;
$config['skylight_share_buttons'] = false;

// Set to the number of minutes to cache pages for. Set to false for no caching.
// This overrides the setting in skylight.php so is commented by Demo
$config['skylight_cache'] = false;

// Digital object management
$config['skylight_display_thumbnail'] = true;
$config['skylight_link_bitstream'] = true;

// Display common image formats in "light box" gallery?
$config['skylight_lightbox'] = true;
$config['skylight_lightbox_mimes'] = array('image/jpeg', 'image/gif', 'image/png');

// Language and locale settings
$config['skylight_language_default'] = 'en';
$config['skylight_language_options'] = array('en');

$config['skylight_highlight_fields'] = 'dc.title.en,dc.contributor.author,dc.identifier.en';

$config['skylight_homepage_recentitems'] = false;
$config['skylight_homepage_randomitems'] = false;
$config['skylight_homepage_fullwidth'] = true;

?>