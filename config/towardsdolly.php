<?php

$config['skylight_appname'] = 'towardsdolly';

// Uncomment this if you are using a url of the form http://.../art/...
$config['skylight_url_prefix'] = 'towardsdolly';

// set the base url and ga code
if (strpos($_SERVER['HTTP_HOST'], "test") !== false) {
    $config['skylight_ga_code'] = '';
    $config['skylight_solrbase'] = 'http://lac-repo-test14.is.ed.ac.uk:8090/';
    $config['skylight_link_url'] = 'http://lac-archives-dev.is.ed.ac.uk:8081';
}
else {
    $config['skylight_ga_code'] = 'UA-25737241-19';
    $config['skylight_solrbase'] = 'http://lac-repo-live14.is.ed.ac.uk:8090/';
    $config['skylight_link_url'] = 'http://archives.collections.ed.ac.uk';
}

$config['skylight_repository_type'] = 'archivesspace'; // Demo 'dspace'
$config['skylight_repository_version'] = '1';

$config['skylight_theme'] = 'towardsdolly';

//$config['skylight_handle_prefix'] = '/repositories/2/resources/85257/';
$config['skylight_handle_prefix'] = '/repositories/2/';

$config['skylight_fullname'] = 'Towards Dolly';

$config['skylight_adminemail'] = 'lddt@mlist.is.ed.ac.uk';

$config['skylight_oaipmhcollection'] = '';

$config['skylight_oaipmhallowed'] = true;


// Container ID and the field used in solr index to store this ID. Used for restricting search/browse scope.
$config['skylight_container_id'] = array(
    '"/repositories/2/resources/85257"', '"/repositories/2/resources/85826"','"/repositories/2/resources/85711"',
    '"/repositories/2/resources/85712"', '"/repositories/2/resources/85258"','"/repositories/2/resources/40"',
    '"/repositories/2/resources/84761"', '"/repositories/2/resources/85246"','"/repositories/2/resources/85804"',
    '"/repositories/2/resources/85271"', '"/repositories/2/resources/85713"','"/repositories/2/resources/85760"',
    '"/repositories/2/resources/83825"', '"/repositories/2/resources/85754"','"/repositories/2/resources/85829"',
    '"/repositories/2/resources/85710"', '"/repositories/2/resources/85824"','"/repositories/2/resources/85862"',
    '"/repositories/2/resources/85835"','"/repositories/2/archival_objects/17088"');
$config['skylight_container_field'] = 'resource';
$config['skylight_sitemap_type'] = 'external';

$config['skylight_query_restriction'] = array('publish' => 'true');

$config['skylight_fields'] = array('Title' => 'title',
    'Creator' => 'creators',
    'Subject' => 'subjects',
    'Type' => 'primary_type',
    'Level' => 'level',
    'Resource_Id' => 'resource',
    'Date' => 'create_time',
    'JSON' => 'json',
    'Agent' => 'agents',
    'Publish' => 'publish',
    'Notes' => 'notes',
    'Language' => 'langmaterial',
    'Scope and Contents' => 'scopecontent',
    'Related' => 'relatedmaterial',
    'Physical' => 'phystech',
    'Access' => 'accessrestrict',
    'Rights' => 'rights_statements',
    'Dates' =>'dates',
    'Extent' => 'extents',
    'Identifier' =>'component_id',
    'Parent' => 'parent',
    'Parent_Id' => 'parent_id',
    'Parent_Type' => 'parent_type',
    'Bibliography' => 'note_bibliography',
    'Id' => 'id',
    'Alternative Format' => 'altformavail',
    'Physical Description' => 'physdesc'

);

$config['skylight_date_filters'] = array();
$config['skylight_filters'] = array('Subject' => 'subjects', 'Person' => 'agents');
$config['skylight_filter_delimiter'] = ':';

$config['skylight_meta_fields'] = array('Title' => 'title',
    'Agent' => 'agents',
    'Subject' => 'subjects',
    'Type' => 'primary_type',
    'Level' => 'level',);

$config['skylight_recorddisplay'] = array('Identifier','Creator','Dates','Extent','Extent Type','Agent','Subject',
    'Rights','Language','Scope and Contents','Related','Bibliography','Physical','Physical Description',
    'Access','Alternative Format' );

$config['skylight_searchresult_display'] = array('Title','Creator','Subject','Agent');

$config['skylight_search_fields'] = array('Title' => 'title',
    'Subject' => 'subjects',
    'Agent' => 'agents',
    'Creator' => 'creators'
);

$config['skylight_sort_fields'] = array('Title' => 'title_sort');

$config['skylight_related_fields'] = array('Parent' => 'parent', 'Id' => 'id');

$config['skylight_feed_fields'] = array('Title' => 'title',
    'Creator' => 'creator',
    'Subject' => 'subjects',
    'Agent' => 'agents');

$config['skylight_results_per_page'] = 10;
$config['skylight_share_buttons'] = false;

$config['skylight_homepage_recentitems'] = false;
$config['skylight_homepage_randomitems'] = false;
$config['skylight_related_number'] = 20;

// Set to the number of minutes to cache pages for. Set to false for no caching.
// This overrides the setting in skylight.php so is commented by Demo
$config['skylight_cache'] = false;

// Digital object management
$config['skylight_bitstream_field'] = '';
$config['skylight_thumbnail_field'] = '';
$config['skylight_display_thumbnail'] = false;
$config['skylight_link_bitstream'] = false;


// Display common image formats in "light box" gallery?
$config['skylight_lightbox'] = true;
$config['skylight_lightbox_mimes'] = array('image/jpeg', 'image/gif', 'image/png');

// Language and locale settings
$config['skylight_language_default'] = 'en';
$config['skylight_language_options'] = array('en');


?>