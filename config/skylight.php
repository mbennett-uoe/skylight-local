<?php

// hostnames
$config['skylight_hostnames'] = array('www.fairbairn.ac.uk', 'test.fairbairn.ac.uk','test.exampapers.ed.ac.uk', 'exampapers.ed.ac.uk', 'test.scottishgovernmentyearbooks.ed.ac.uk', 'www.scottishgovernmentyearbooks.ed.ac.uk','test.openbooks.is.ed.ac.uk','openbooks.is.ed.ac.uk','test.sopacollection.ph.ed.ac.uk','sopacollection.ph.ed.ac.uk', 'test.exhibitions.ed.ac.uk', 'exhibitions.ed.ac.uk', 'test.lac-projects.is.ed.ac.uk', 'lac-projects.is.ed.ac.uk', 'test.stuartsound.is.ed.acuk', 'www.stuartsound.is.ed.ac.uk');

// Uncomment this if you want to use urls of the form http://.../prefix/...
$config['skylight_url_prefixes'] = array('mimed', 'art', 'calendars', 'iconics', 'towardsdolly', 'alumni', 'coimbra', 'public-art');

$config['skylight_handle_prefixes'] = array(3 => "art", 11 => "mimed");

// The URL of the parent solr server
//$config['skylight_solrbase'] = 'http://localhost:9124/solr/search/';
$config['skylight_solrbase'] = 'http://collectionssolr.is.ed.ac.uk/search/';

//DSpace handle server prefix
$config['skylight_handle_prefix'] = '10683';

// The platform and version of your repository.
// Currently DSpace 1.7.1+ is the only supported repository
$config['skylight_repository_type'] = 'dspace'; // Demo 'dspace'
$config['skylight_repository_version'] = '181'; // Demo '171'

// The local path for theme and configuration overrides (if required)
$config['skylight_local_path'] = '../skylight-local';

// The main username and password (by Demo admin:admin)
$config['skylight_adminusername'] = '';
$config['skylight_adminpassword'] = '';

// Whether to use LDAP for admin authentication
$config['skylight_adminldap'] = False;
$config['skylight_adminldap_server'] = "ldaps://ldap.example.com:636";
$config['skylight_adminldap_context'] = "ou=users,dc=example,dc=com";
$config['skylight_adminldap_allowed'] = array('id1', 'id2');

// The OAI-PMH base for the parent server
$config['skylight_oaipmhbase'] = 'http://collectionsinternal.is.ed.ac.uk:8080/oai/request?';

// The OAI-PMH identifier to replace in OAI-PMH responses
$config['skylight_oaipmhid']= 'oai:collectionsmanager.is.ed.ac.uk:10683/';

// The link in OAI-PMH responses to replace with the skylight record URL
$config['skylight_oaipmhlink'] = 'http://hdl.handle.net/10683/';

// The URL base for where digital objects can be proxied from
$config['skylight_objectproxy_url'] = 'http://collectionsinternal.is.ed.ac.uk:8080/bitstream/10683/';

// Default OAI not permitted
$config['skylight_oaipmhallowed'] = false;

$config['skylight_media_url_prefix'] = 'http://collectionsmedia.is.ed.ac.uk/';

// Set to the number of minutes to cache pages for. Set to false for no caching.
// This can be overridden in site-specific configuration files.
$config['skylight_cache'] = false;

// Keys required for the recapthca system
$config['skylight_recaptcha_key_public'] = '';
$config['skylight_recaptcha_key_private'] = '';

// Digital object management
$config['skylight_bitstream_field'] = 'dc.format.original';
$config['skylight_thumbnail_field'] = 'dc.format.thumbnail';
$config['skylight_display_thumbnail'] = true;
$config['skylight_link_bitstream'] = true;

// Default options (required as may not be set in other config files)
$config['skylight_homepage_recentitems'] = true;
$config['skylight_facet_limit'] = 10;

// Spellchecking / Spelling suggestions
// Dictionaries must be set up in your local solr configuration
$config['skylight_solr_dictionary'] = 'default';

// Used for Related Items
$config['skylight_related_fields'] = array('Title' => 'dc.title.en', 'Subject' => 'dc.subject.en');
$config['skylight_related_number'] = 10;

/**
 * Debug / development options.
 *
 * We recommend that these are disabled (or commented out) for production systems
 */

// Set to true to enable debugging / profiling information
// $config['skylight_debug'] = false;

// Can configuration files be overwritten by the user ?config={vhostname}
$config['skylight_config_allowoverride'] = false;

// Can themes be overridden by the user using ?theme={themename}
$config['skylight_theme_allowoverride'] = false;

?>
