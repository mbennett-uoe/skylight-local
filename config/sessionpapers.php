<?php

$config['skylight_appname'] = 'sessionpapers';

$config['skylight_theme'] = 'sessionpapers';

$config['skylight_fullname'] = 'Scottish Court of Session Papers';

$config['skylight_adminemail'] = 'lddt@mlist.is.ed.ac.uk';

$config['skylight_oaipmhcollection'] = ''; # TODO: Obtain / set this

// Uncomment this if you are using a url of the form http://.../art/...
$config['skylight_url_prefix'] = 'sessionpapers';

// Container ID and the field used in solr index to store this ID. Used for restricting search/browse scope.
$config['skylight_container_id'] = '70'; # TODO: Obtain / set this
$config['skylight_container_field'] = 'location.coll';

$config['skylight_sitemap_type'] = 'internal';


$config['skylight_fields'] = array('Title' => 'dc.title.en',
    'Reference' => 'dc.identifier.other',
    'Link' => 'dc.identifier.uri',
    'Date' => 'dc.coverage.temporal',
    'Bitstream'=> 'dc.format.original.en',
    'Thumbnail'=> 'dc.format.thumbnail.en',
    'Description'=>'dc.description.en',
    'Format' => 'dc.format.en',
    'Shelf Mark' => 'dc.identifier.other'
);


$config['skylight_date_filters'] = array('Date' => 'dateIssued.year_sort');
$config['skylight_filters'] = array('Subject' => 'subject_filter');
$config['skylight_filter_delimiter'] = ':';

$config['skylight_meta_fields'] = array('Title' => 'dc.title',
    'Subject' => 'dc.subject',
    'Date' => 'dc.date.issued',
);

$config['skylight_recorddisplay'] = array('Title','Subject');

$config['skylight_searchresult_display'] = array('Title','Subject');

$config['skylight_search_fields'] = array('Keywords' => 'text',
    'Subject' => 'dc.subject'
);

$config['skylight_sort_fields'] = array('Title' => 'dc.title',
    'Date' => 'dc.date.issued_dt'
);

$config['skylight_feed_fields'] = array('Title' => 'Title',
    'Author' => 'Author',
    'Subject' => 'Subject'
);

$config['skylight_results_per_page'] = 10;
$config['skylight_share_buttons'] = false;

// $config['skylight_homepage_recentitems'] = false;

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
$config['skylight_language_options'] = array('en', 'ko', 'jp');


?>