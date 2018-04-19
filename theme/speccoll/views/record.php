<?php

$title_field = $this->skylight_utilities->getField('Title');
$author_field = $this->skylight_utilities->getField('Author');
$shelfmark_field =  $this->skylight_utilities->getField('Shelfmark');
$date_field = $this->skylight_utilities->getField('Date Made');
$bitstream_field = $this->skylight_utilities->getField('Bitstream');
$thumbnail_field = $this->skylight_utilities->getField('Thumbnail');
$filters = array_keys($this->config->item("skylight_filters"));
$placedisplay = $this->config->item("skylight_placedisplay");
$measurementdisplay = $this->config->item("skylight_measurementdisplay");
$associationdisplay = $this->config->item("skylight_associationdisplay");
$locationdisplay = $this->config->item("skylight_locationdisplay");
$datedisplay = $this->config->item("skylight_datedisplay");
$identificationdisplay = $this->config->item("skylight_identificationdisplay");
$descriptiondatadisplay = $this->config->item("skylight_descriptiondatadisplay");
$typedisplay = $this->config->item("skylight_typedisplay");
$link_uri_field = $this->skylight_utilities->getField("ImageURI");
$short_field = $this->skylight_utilities->getField("Short Description");
$date_field = $this->skylight_utilities->getField("Date");
$media_uri = $this->config->item("skylight_media_url_prefix");
$theme = $this->config->item("skylight_theme");
$acc_no_field = $this->skylight_utilities->getField("Accession Number");

$type = 'Unknown';
$mainImageTest = false;
$numThumbnails = 0;
$bitstreamLinks = array();
$image_id = "";

// booleans for video/audio
$mainImage = false;
$videoFile = false;
$audioFile = false;
$audioLink = "";
$videoLink = "";

require_once('SimpleRDFElement.php');

if(isset($solr[$bitstream_field]) && $link_bitstream) {

    foreach ($solr[$bitstream_field] as $bitstream_for_array) {
        $b_segments = explode("##", $bitstream_for_array);
        $b_seq = $b_segments[4];
        $bitstream_array[$b_seq] = $bitstream_for_array;
    }

    ksort($bitstream_array);

    $mainImage = false;
    $videoFile = false;
    $audioFile = false;

    $b_seq = "";

    foreach ($bitstream_array as $bitstream) {
        $mp4ok = false;
        $b_segments = explode("##", $bitstream);
        $b_filename = $b_segments[1];
        if ($image_id == "") {
            $image_id = substr($b_filename, 0, 7);
        }
        $b_handle = $b_segments[3];
        $b_seq = $b_segments[4];
        $b_handle_id = preg_replace('/^.*\//', '', $b_handle);
        $b_uri = './record/' . $b_handle_id . '/' . $b_seq . '/' . $b_filename;
        $hasalma = 'N';
        if ((strpos($b_filename, ".json") > 0) or (strpos($b_filename, ".JSON") > 0))
        {
            $manifest = base_url() . 'speccoll/record/' . $b_handle_id . '/' . $b_seq . '/' . $b_filename;


            $json = file_get_contents($manifest);
            $jobj = json_decode($json, true);
            $error = json_last_error();

            $linkURI = $jobj['related'];
            $linkURI = str_replace('detail', 'iiif', $linkURI);
            $linkURI = $linkURI.'/full/!300,300/0/default.jpg';

            $jsonLink = '<span class ="json-link-item"><a href="https://librarylabs.ed.ac.uk/iiif/uv/?manifest=' . $manifest . '" target="_blank" class="uvlogo" title="View in UV"></a></span>';
            $jsonLink .= '<span class ="json-link-item"><a target="_blank" title="View in Mirador" href="https://librarylabs.ed.ac.uk/iiif/mirador/?manifest='.$manifest.'" class="miradorlogo"></a></span>';
            //  $jsonLink .= '<span class ="json-link-item"><a href="https://images.is.ed.ac.uk/luna/servlet/view/search?search=SUBMIT&q=' . $accno . '" class="lunalogo" title="View in LUNA"></a></span>';
            $jsonLink .= '<span class ="json-link-item"><a href="' . $manifest . '" target="_blank"  class="iiiflogo" title="IIIF manifest"></a></span>';
            //$jsonLink .= '<span class ="json-link-item"><a href = "https://creativecommons.org/licenses/by/3.0/" class ="ccbylogo" title="All images CC-BY" target="_blank" ></a></span>';

            foreach ( $jobj['sequences'][0]['canvases'][0]['metadata']as $metadatapair) {
                $label = $metadatapair['label'];
                $value = $metadatapair['value'];

                if (strpos($value, "discovered") !== false) {
                    $value = str_replace("<span>", "",$value);
                    $value = str_replace("</span>","",$value);
                    $primourl = $value;
                    $hasprimo = 'Y';
                   // $almaurl = "https://open-na.hosted.exlibrisgroup.com/alma/44UOE_INST/rda/entity/manifestation/". substr($value, $UOE + 10, 17).".rdf";
                    
                }

                if ($label == 'Catalogue Number')
                {
                    $value =str_replace("<span>","",$value);
                    $value =str_replace("</span>","",$value);
                    //RDF not going well
                   // $almaurl = "https://open-na.hosted.exlibrisgroup.com/alma/44UOE_INST/rda/entity/manifestation/".$value.".rdf";
                    //try JSON-LD
                    $almaurl = "https://open-na.hosted.exlibrisgroup.com/alma/44UOE_INST/bibs/".$value;

                    $hasalma = 'Y';
                }
            }
        }
    }
}
?>

<nav class="navbar navbar-fixed-top second-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#record-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div>
            <div class="collapse navbar-collapse" id="record-navbar">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo $_SERVER['REQUEST_URI'];?>#stc-section1">Top</a></li>
                    <li><a href="<?php echo $_SERVER['REQUEST_URI'];?>#stc-section2">Image</a></li>
                    <li><a href="<?php echo $_SERVER['REQUEST_URI'];?>#stc-section3">Description</a></li>
                    <?php if($audioLink != '') {
                        echo '<li ><a href ="'.$_SERVER['REQUEST_URI'].'#stc-section4" >Audio-Visual</a ></li >';
                    } ?>
                    <li><a href="<?php echo $_SERVER['REQUEST_URI'];?>#stc-section5">Catalogue Data</a></li>
                    <li><a href="<?php echo $_SERVER['REQUEST_URI'];?>#stc-section6">Related Items</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<?php


foreach($recorddisplay as $key)
{
    $element = $this->skylight_utilities->getField($key);

    if(isset($solr[$element]))
    {
        foreach($solr[$element] as $index => $metadatavalue)
        {
            // if it's a facet search
            // make it a clickable search link

            if($key == 'Date') {
                $date = $metadatavalue;
            }
            if (!(isset($date))){
                $date = 'Undated';
            }
            if($key == 'Author') {
                $maker = $metadatavalue;
            }
            if (!(isset($maker))){
                $maker = 'Unknown author';
            }
            if($key == 'Title') {
                $title = $metadatavalue;
            }
            if (!(isset($title))){
                $title = 'Unnamed item';
            }

            if($key == 'Shelfmark') {
                $date = $metadatavalue;
            }
        }
    }
}

?>

<div id="stc-section1" class="container-fluid record-content">
    <h2 class="itemtitle hidden-sm hidden-xs"><?php echo $title .' | '. $maker. ' | '.$date;?></h2>
    <h4 class="itemtitle hidden-lg hidden-md"><?php echo $title .' | '. $maker. ' | '.$date;?></h4>
</div>

<div id="stc-section2" class="container-fluid">
<?php
if (isset($jsonLink)) {

    $viewlink = '<td><iframe src="https://librarylabs.ed.ac.uk/iiif/uv/?manifest='.$manifest.'" width = "1300" height = "800" ></iframe></td>';

echo $viewlink;
}
    ?>
    <div class = "json-link">
        <p>
            <?php if (isset($jsonLink)){echo $jsonLink;} ?>
        </p>
    </div>
</div>

<div id="stc-section5" class="panel panel-default container-fluid">
    <div class="panel-heading straight-borders">
        <h2 class="panel-title hidden-sm hidden-xs ">
            <a class="accordion-toggle" data-toggle="collapse" href="#collapse1">Catalogue Data <i class="fa fa-chevron-down" aria-hidden="true"></i>
            </a>
        </h2>
        <h4 class="panel-title hidden-md hidden-lg ">
            <a class="accordion-toggle" data-toggle="collapse" href="#collapse1">Catalogue Data <i class="fa fa-chevron-down" aria-hidden="true"></i>

            </a>
        </h4>
    </div>


    <?php
    if ($hasalma == 'Y' or $hasprimo == 'Y')
    {

        //fclose($fp);
        // echo $almaurl;
        //$almaXML = new SimpleXMLElement($response);
/*
        $xml_file =file_get_contents("/var/tmp/curl.xml");
        /*
            $sentic_replace = str_replace("rdf:resource", "", $xml_file);
            $sentic_replace = str_replace("rdaw:", "rdaw_", $sentic_replace);
            $sentic_replace = str_replace("rdac:", "rdac_", $sentic_replace);
            $sentic_replace = str_replace("rdae:", "rdae_", $sentic_replace);
            $sentic_replace = str_replace("rdam:", "rdam_", $sentic_replace);
            $sentic_replace = str_replace("rdf:", "rdf_", $sentic_replace);
        &/

          // print_r($sentic_replace);
        $almaXML =simplexml_load_string($response, 'SimpleRDFElement');
        //

        if ($almaXML == FALSE)
        {
            echo "Failed loading XML\n";

            foreach (libxml_get_errors() as $error)
            {
                echo "\t", $error->message;
            }
        }
/*
        $almaXML->registerXPathNamespace('madsrdf','http://www.loc.gov/mads/rdf/v1#');
        $almaXML->registerXPathNamespace('rdfs','http://www.w3.org/2000/01/rdf-schema#');
        $almaXML->registerXPathNamespace('rdf','http://www.w3.org/1999/02/22-rdf-syntax-ns#');
        $almaXML->registerXPathNamespace('rdac','http://rdaregistry.info/Elements/c/');
        $almaXML->registerXPathNamespace('rdaw','http://rdaregistry.info/Elements/w/');
        $almaXML->registerXPathNamespace('rdae','http://rdaregistry.info/Elements/e/');
        $almaXML->registerXPathNamespace('rdam','http://rdaregistry.info/Elements/m/');
        $almaXML->registerXPathNamespace('rdai','http://rdaregistry.info/Elements/i/');
        $almaXML->registerXPathNamespace('rdaa','http://rdaregistry.info/Elements/a/');
        // var_dump($almaXML);


        $rdf = $almaXML->xpath('rdf:RDF');

        print_r($rdf);
        /*
 * */
        /*
        print_r($almaXML);
        foreach ($almaXML->children() as $object)
        {
          print_r($object);
        }


        //echo ' <iframe src="'.$almaurl.'" width = "1300" height = "800" ></iframe>';
        */

        if ($hasprimo == 'Y') {
            echo '<p><a href= "' . $primourl . '" target="_blank">See this item on DiscoverEd.</a></p>';
        }

        if ($hasalma == 'Y' )
        {
            $curl = curl_init();
            // $fp = fopen("/var/tmp/curl.json", "w");
            curl_setopt($curl, CURLOPT_URL, $almaurl);
            //curl_setopt($curl, CURLOPT_FILE, $fp);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ( $httpCode == 404 ) {
                echo "404";
            }
            else
            {
                //fwrite($fp, $response);
                $alma_json = json_decode($response, true);

            }
            // var_dump($response);
            curl_close($curl);
            echo "<h3>Main volume data</h3>";

            foreach($alma_json as $key=>$value)
            {
                if (($key !== 'creator') and ($key !== 'note')and ($key !== 'contributor') and ($key !== 'identifier'))
                {
                    $key =str_replace("@", "", $key);
                    echo "<p>".ucfirst($key)." : ".$value."</p>";
                }
            }


            echo "<h3>Creators</h3>";

            foreach ($alma_json['creator'] as $key=>$value)
            {

                if ($key == 'label'){
                    $creatorlabel = $value;
                }

                if ($key = 'sameAs') {
                    $viaf = '<a href = "' . $value . '" target = "_blank"><sup>VIAF</sup></a>';
                }

                if ($key = '@id')
                {
                    $lc = '<a href = "' . $value . '" target = "_blank"><sup>LC</sup></a>';
                }

            }
            $creatorarray[] = "<p>" . $creatorlabel . "<sup>" . $viaf . "|" . $lc . "</sup></p>";

            foreach ($creatorarray as $creatordisplay) {
                echo $creatordisplay;
            }

            echo "<h3>Contributors</h3>";

            $i = 0;
            foreach ($alma_json['contributor'] as $key=>$value)
            {

                $contributorlabel = $alma_json['contributor'][$i]['label'];
                $contviaf = '<a href = "' . $alma_json['contributor'][$i]['sameAs'] . '" target = "_blank"><sup>VIAF</sup></a>';
                $contlc = '<a href = "' . $alma_json['contributor'][$i]['@id'] . '" target = "_blank"><sup>LC</sup></a>';

                $contributorarray[$i] = "<p>" . $contributorlabel . "<sup>" . $contviaf . "|" . $contlc . "</sup></p>";
                $i++;
            }

            foreach ($contributorarray as $contributordisplay) {
                echo $contributordisplay;
            }

            echo "<h3>Notes</h3>";
            $j=1;
            foreach ($alma_json['note'] as $note)
            {

                echo "<p>" .$j. ". ".$note."</p>";
                $j++;
            }

            echo "<h3>Identifiers</h3>";
            foreach ($alma_json['identifier'] as $identifierlabel=> $identifiervalue) {
                //foreach($identifierkey as $identifierlabel=>$identifiervalue)
                // {
                echo "<p>" . $identifierlabel . " : " . $identifiervalue . "</p>";
                // }
            }
        }

    }


   foreach($recorddisplay as $key) {

        $element = $this->skylight_utilities->getField($key);

        if(isset($solr[$element])) {

            foreach($solr[$element] as $index => $metadatavalue) {
                echo '<div class="stc-tags">';

                // if it's a facet search
                // make it a clickable search link
                if(in_array($key, $filters)) {
                    if (!strpos($metadatavalue, "/")> 0)
                    {
                        $orig_filter = urlencode($metadatavalue);
                        $lower_orig_filter = strtolower($metadatavalue);
                        $lower_orig_filter = urlencode($lower_orig_filter);

                        echo '<a href="./search/*:*/' . $key . ':%22' . $lower_orig_filter . '%7C%7C%7C' . $orig_filter . '%22">' . $metadatavalue . '</a>';
                    }
                }
                echo '</div>';

            }
        }
    }
    ?>
</div>



