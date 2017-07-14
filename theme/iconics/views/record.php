<?php

$author_field = $this->skylight_utilities->getField("Author");
$type_field = $this->skylight_utilities->getField("Type");
$bitstream_field = $this->skylight_utilities->getField("Bitstream");
$description_field = $this->skylight_utilities->getField("Description");
$abstract_field = $this->skylight_utilities->getField("Abstract");
$thumbnail_field = $this->skylight_utilities->getField("Thumbnail");
$date_field = $this->skylight_utilities->getField("Date");
$filters = array_keys($this->config->item("skylight_filters"));
$link_uri_field = $this->skylight_utilities->getField("Link");
$tags_field = $this->skylight_utilities->getField("Tags");
$container = $this->config->item('skylight_container_field');
$container_id = $this->config->item('skylight_container_id');
$navigation = $this->solr_client->getNavigation($id, $container."%3A%28".$container_id."%29", $container);

$media_uri = $this->config->item("skylight_media_url_prefix");

$type = 'Unknown';
$mainImageTest = false;
$numThumbnails = 0;
$bitstreamLinks = array();
$image_id = "";

if(isset($solr[$type_field])) {
    $type = "media-" . strtolower(str_replace(' ','-',$solr[$type_field][0]));
}

        $numThumbnails = 0;
        foreach ($solr[$link_uri_field] as $linkURI) {
            if (strpos($linkURI, 'luna') > 0) {
                //just for test, this line!
                //$tileSource = str_replace('images.is.ed.ac.uk', 'lac-luna-test2.is.ed.ac.uk:8181', $linkURI);
                $tileSource = str_replace('detail', 'iiif', $linkURI) . '/info.json';
                $iiifmax = str_replace('info.json', 'full/full/0/default.jpg', $tileSource);
                list($width, $height) = getimagesize($iiifmax);
                //echo 'WIDTH'.$width.'HEIGHT'.$height
                $portrait = true;
                if ($width > $height) {
                    $portrait = false;
                }
                $mainImage = false;
                $json = file_get_contents($tileSource);
                $jobj = json_decode($json, true);
                $error = json_last_error();
                $jsoncontext = $jobj['@context'];
                $jsonid = $jobj['@id'];
                $jsonheight = $jobj['height'];
                $jsonwidth = $jobj['width'];
                $jsonprotocol = $jobj['protocol'];
                $jsontiles = $jobj['tiles'];
                $jsonprofile = $jobj['profile'];
                if (!$mainImage) {
                    $mainImageTest = true;
                    ?>
                    <div class="full-image">
                        <div id="openseadragon">
                            <script type="text/javascript">
                                OpenSeadragon({
                                    id: "openseadragon",
                                    prefixUrl: "<?php echo base_url();?>assets/openseadragon/images/",
                                    preserveViewport: false,
                                    visibilityRatio: 1,
                                    minZoomLevel: 0.7,
                                    defaultZoomLevel: 3,
                                    panHorizontal: true,
                                    sequenceMode: true,
                                    tileSize: 500,
                                    tileSources: [{
                                        "@context": "<?php echo $jsoncontext ?>",
                                        "@id": "<?php echo $jsonid ?>",
                                        "height": <?php echo $jsonheight ?>,
                                        "width": <?php echo $jsonwidth ?>,
                                        "profile": ["http://iiif.io/api/image/2/level2.json",
                                            {
                                                "formats": ["gif", "pdf"]
                                            }
                                        ],
                                        "protocol": "<?php echo $jsonprotocol ?>",
                                        "tiles": [{
                                            "scaleFactors": [1, 2, 8, 16, 32],
                                            "width": 512
                                        }]
                                        //minLevel: 2
                                    }]
                                });
                            </script>
                        </div>
                    </div>
                    <?php
                }
            }
        }

if(isset($solr[$bitstream_field]) && $link_bitstream) {

    foreach ($solr[$bitstream_field] as $bitstream_for_array)
    {
        $b_segments = explode("##", $bitstream_for_array);
        $b_seq = $b_segments[4];
        $bitstream_array[$b_seq] = $bitstream_for_array;
    }

    ksort($bitstream_array);

    $mainImage = false;
    $videoFile = false;
    $audioFile = false;
    $audioLink = "";
    $videoLink = "";
    $b_seq =  "";

    foreach($bitstream_array as $bitstream) {
        $mp4ok = false;
        $b_segments = explode("##", $bitstream);
        $b_filename = $b_segments[1];
        $image_id = substr($b_filename,0,7);
        $b_handle = $b_segments[3];
        $b_seq = $b_segments[4];
        $b_handle_id = preg_replace('/^.*\//', '',$b_handle);
        $b_uri = './record/'.$b_handle_id.'/'.$b_seq.'/'.$b_filename;

        if ((strpos($b_uri, ".mp3") > 0) or (strpos($b_uri, ".MP3") > 0)) {

            $audioLink .= '<audio controls>';
            $audioLink .= '<source src="' . $b_uri . '" type="audio/mpeg" />Audio loading...';
            $audioLink .= '</audio>';
            $audioFile = true;

        }

        else if ((strpos($b_filename, ".mp4") > 0) or (strpos($b_filename, ".MP4") > 0))
        {
            $b_uri = $media_uri.$b_handle_id.'/'.$b_seq.'/'.$b_filename;
            // Use MP4 for all browsers other than Chrome
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == false)
            {
                $mp4ok = true;
            }
            //Microsoft Edge is calling itself Chrome, Mozilla and Safari, as well as Edge, so we need to deal with that.
            else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') == true)
            {
                $mp4ok = true;
            }

            if ($mp4ok == true)
            {
                $videoLink .= '<div class="flowplayer" data-analytics="' . $ga_code . '" title="' . $record_title . ": " . $b_filename . '">';
                $videoLink .= '<video preload=auto loop width="100%" height="auto" controls preload="true" width="660">';
                $videoLink .= '<source src="' . $b_uri . '" type="video/mp4" />Video loading...';
                $videoLink .= '</video>';
                $videoLink .= '</div>';
                $videoFile = true;
            }
        }

        else if ((strpos($b_filename, ".webm") > 0) or (strpos($b_filename, ".WEBM") > 0))
        {
            //Microsoft Edge needs to be dealt with. Chrome calls itself Safari too, but that doesn't matter.
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') == false)
            {
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == true)
                {
                    $b_uri = $media_uri . $b_handle_id . '/' . $b_seq . '/' . $b_filename;
                    // if it's chrome, use webm if it exists
                    $videoLink .= '<div class="flowplayer" data-analytics="' . $ga_code . '" title="' . $record_title . ": " . $b_filename . '">';
                    $videoLink .= '<video preload=auto loop width="100%" height="auto" controls preload="true" width="660">';
                    $videoLink .= '<source src="' . $b_uri . '" type="video/webm" />Video loading...';
                    $videoLink .= '</video>';
                    $videoLink .= '</div>';
                    $videoFile = true;
                }
            }
        }

        ?>
    <?php
    }

}
?>

<div class="content">

    <?php if($mainImageTest === true) { ?>
    <div class="full-title">
        <?php } ?>
        <div class="title-header">

            <h1 class="itemprev">
                <a href="./record/<?php echo $navigation['prev']; ?>" title="View Previous Item"><i class="fa fa-arrow-left">&nbsp;&nbsp;</i></a>
            </h1>
            <h1 class="item-title">
                <?php echo $record_title ?>
                <?php if(isset($solr[$date_field])) {
                    echo " (" . $solr[$date_field][0] . ")";
                } ?>

            </h1>
            <h1 class="itemnext">
                <a href="./record/<?php echo $navigation['next']; ?>" title="View Next Item"><i class="fa fa-arrow-right"></i></a>
            </h1>

        </div>
        <div class="clearfix"></div>
        <div class="item-abstract">
            <?php
            if (array_key_exists ($abstract_field, $solr)){
                echo $solr[$abstract_field][0];
            }?>

        </div>
        <?php if($mainImageTest === true) { ?>
    </div>
<?php if($mainImage) { ?>
    <div class="full-image">
        <?php echo $bitstreamLink; ?>
    </div>
<?php } } ?>

    <?php if($mainImageTest === true) { ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="maintext">
                <?php
                if (array_key_exists ($description_field, $solr)){
                    echo $solr[$description_field][0];
                }?>
            </div>

            <?php } ?>

            <?php $excludes = array("");

            foreach($recorddisplay as $key) {

                $element = $this->skylight_utilities->getField($key);

                if(isset($solr[$element])) {
                    if(!in_array($key, $excludes)) {
                        echo '<div class="metadatarow"><div class="metadatakey">'.$key.'</div><div class="metadatavalue">';
                        foreach($solr[$element] as $index => $metadatavalue) {
                            // if it's a facet search
                            // make it a clickable search link
                            if(in_array($key, $filters) && $key != "Author") {

                                $orig_filter = urlencode($metadatavalue);
                                $lower_orig_filter = strtolower($metadatavalue);
                                $lower_orig_filter = urlencode($lower_orig_filter);

                                echo '<a href="./search/*:*/' . $key . ':%22'.$lower_orig_filter.'%7C%7C%7C'.$orig_filter.'%22">'.$metadatavalue.'</a>';
                            }
                            else {
                                echo $metadatavalue;
                            }

                            if($index < sizeof($solr[$element]) - 1) {
                                echo '; ';
                            }
                        }
                        echo '</div></div>';
                    }
                }

            }
            if($mainImageTest === true) { ?>
    </div>
    </div>
<?php }
    $manifestURI = str_replace('detail', 'iiif/m',$linkURI);?>

    <div>
        <p>
            <a target="_blank" href="<?php echo $manifestURI; ?>/manifest">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/International_Image_Interoperability_Framework_logo.png" class="iiiflogo" title="Right-click, Copy Link to get the full IIIF manifest for the collection."></a>
            <a target="_blank" href="<?php echo $manifestURI; ?>/manifest">
                <img src="http://images.is.ed.ac.uk/luna/images/LUNAIIIF80.png" class="lunaiiif" title="Right-click, Copy Link to get the full IIIF manifest for the collection."></a>
            This collection is IIIF-compliant. <a href ="./iiif">See more</a>.
        </p>
    </div>
    <div class="clearfix"></div>
    <!-- print out crowdsourced tags -->
    <?php

    if(isset($solr[$tags_field])) {?>
        <div class="crowd-tags"><span class="crowd-title" title="User generated tags created through crowd sourcing games"><i class="fa fa-users fa-lg" >&nbsp;</i>Tags:</span>
            <?php foreach($solr[$tags_field] as $tag) {

                $orig_filter = urlencode($tag);
                $lower_orig_filter = strtolower($tag);
                $lower_orig_filter = urlencode($lower_orig_filter);
                echo '<span class="crowd-tag">' . '<a href="./search/*:*/Tags:%22'.$lower_orig_filter.'%7C%7C%7C'.$orig_filter.'%22"><i class="fa fa-tags fa-lg">&nbsp;</i>'.$tag.'</a>' . '</span>';
            } ?>
            <div class="crowd-info">
                <form id="libraylabs" method="get" action="http://librarylabs.ed.ac.uk/games/gameCrowdSourcing.php" target="_blank">
                    <input type="hidden" name="image_id" value="<?php echo $image_id ?>">
                    <input type="hidden" name="theme" value="classic">
                    Add more tags at <a href="#" onclick="document.forms[1].submit();return false;" title="University of Edinburgh, Library Labs Metadata Games">Library Labs Games</a>
                    (Create a login at <a href="https://www.ease.ed.ac.uk/friend/" target="_blank" title="EASE Friend">Edinburgh Friend Account</a>)
                </form>
            </div>
        </div>

    <?php }

    else {

        ?>
        <div class="crowd-tags">
            <div class="crowd-info">
                <form id="libraylabs" method="get" action="http://librarylabs.ed.ac.uk/games/gameCrowdSourcing.php" target="_blank">
                    <input type="hidden" name="image_id" value="<?php echo $image_id ?>">
                    <input type="hidden" name="theme" value="classic">
                    Add tags to this image at <a href="#" onclick="document.forms[1].submit();return false;" title="University of Edinburgh, Library Labs Metadata Games">Library Labs Games</a>
                    (Create a login at <a href="https://www.ease.ed.ac.uk/friend/" target="_blank" title="EASE Friend">Edinburgh Friend Account</a>)
                </form>
            </div>
        </div>


    <?php
    }

    if(isset($solr[$bitstream_field]) && $link_bitstream) {

        echo '<div class="record_bitstreams">';

        $i = 0;
        $newStrip = false;
        if($numThumbnails > 0) {

            echo '<div class="thumbnail-strip">';

            foreach($thumbnailLink as $thumb) {

                if($newStrip)
                {

                    echo '</div><div class="clearfix"></div>';
                    echo '<div class="thumbnail-strip">';
                    echo $thumb;
                    $newStrip = false;
                }
                else {

                    echo $thumb;
                }

                $i++;

                // if we're starting a new thumbnail strip
                if($i % 4 === 0) {
                    $newStrip = true;
                }
            }

            echo '</div><div class="clearfix"></div>';
        }

        if($audioFile) {


            echo '<br>.<br>'.$audioLink;
        }

        if($videoFile) {

            echo '<br>.<br>'.$videoLink;
        }

        echo '</div><div class="clearfix"></div>';

    }

    echo '</div>';
    ?>

    <input type="button" value="Back to Search Results" class="backbtn" onClick="history.go(-1);">