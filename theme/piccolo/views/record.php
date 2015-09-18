<?php

$author_field = $this->skylight_utilities->getField("Author");
$maker_field = $this->skylight_utilities->getField("Maker");
$type_field = $this->skylight_utilities->getField("Type");
$bitstream_field = $this->skylight_utilities->getField("Bitstream");
$thumbnail_field = $this->skylight_utilities->getField("Thumbnail");
$filters = array_keys($this->config->item("skylight_filters"));
$link_uri_field = $this->skylight_utilities->getField("Link");
$piccolo_field = $this->skylight_utilities->getField("Piccolo Description");
$date_field = $this->skylight_utilities->getField("Date");

$type = 'Unknown';
$mainImageTest = false;
$numThumbnails = 0;
$bitstreamLinks = array();
$image_id = "";


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

        $b_segments = explode("##", $bitstream);
        $b_filename = $b_segments[1];
        if($image_id == "") {
            $image_id = substr($b_filename,0,7);
        }
        $b_handle = $b_segments[3];
        $b_seq = $b_segments[4];
        $b_handle_id = preg_replace('/^.*\//', '',$b_handle);
        $b_uri = './record/'.$b_handle_id.'/'.$b_seq.'/'.$b_filename;

        if ((strpos($b_uri, ".jpg") > 0) or (strpos($b_uri, ".JPG") > 0))
        {
            if (!$mainImage) {

                // we have a main image
                $mainImageTest = true;

                $bitstreamLink = '<div class="main-image">';

                $bitstreamLink .= '<a title = "' . $record_title . '" class="fancybox" rel="group" href="' . $b_uri . '"> ';
                $bitstreamLink .= '<img class="record-main-image" src = "'. $b_uri .'">';
                $bitstreamLink .= '</a>';

                $bitstreamLink .= '</div>';

                $mainImage = true;

            }
            // we need to display a thumbnail
            else {

                // if there are thumbnails
                if(isset($solr[$thumbnail_field])) {
                    foreach ($solr[$thumbnail_field] as $thumbnail) {

                        $t_segments = explode("##", $thumbnail);
                        $t_filename = $t_segments[1];

                        if ($t_filename === $b_filename . ".jpg") {

                            $t_handle = $t_segments[3];
                            $t_seq = $t_segments[4];
                            $t_uri = './record/'.$b_handle_id.'/'.$t_seq.'/'.$t_filename;

                            $thumbnailLink[$numThumbnails] = '<div class="thumbnail-tile';

                            if($numThumbnails % 4 === 0) {
                                $thumbnailLink[$numThumbnails] .= ' first';
                            }

                            $thumbnailLink[$numThumbnails] .= '"><a title = "' . $record_title . '" class="fancybox" rel="group" href="' . $b_uri . '"> ';
                            $thumbnailLink[$numThumbnails] .= '<img src = "'.$t_uri.'" class="record-thumbnail" title="'. $record_title .'" /></a></div>';

                            $numThumbnails++;
                        }
                    }
                }

            }

        }
        else if ((strpos($b_uri, ".mp3") > 0) or (strpos($b_uri, ".MP3") > 0)) {

            $audioLink .= '<audio id="audio-' . $b_seq;
            $audioLink .= '" title="' . $record_title . ": " . $b_filename . '" ';
            $audioLink .= 'controls preload="true" width="600">';
            $audioLink .= '<source src="' . $b_uri . '" type="audio/mpeg" />Audio loading...';
            $audioLink .= '</audio>';
            $audioFile = true;

        }

        else if ((strpos($b_uri, ".mp4") > 0) or (strpos($b_uri, ".MP4") > 0))
        {

            // if it's chrome, use webm if it exists
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == false) {

                $videoLink .= '<div class="flowplayer" data-analytics="' . $ga_code . '" title="' . $record_title . ": " . $b_filename . '">';
                $videoLink .= '<video id="video-' . $b_seq. '" title="' . $record_title . ": " . $b_filename . '" ';
                $videoLink .= 'controls preload="true" width="600">';
                $videoLink .= '<source src="' . $b_uri . '" type="video/mp4" />Video loading...';
                $videoLink .= '</video>';
                $videoLink .= '</div>';

                $videoFile = true;

            }
        }
        else if ((strpos($b_uri, ".webm") > 0) or (strpos($b_uri, ".WEBM") > 0))
        {

            // if it's chrome, use webm if it exists
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == true) {

                $videoLink .= '<div class="flowplayer" data-analytics="' . $ga_code . '" title="' . $record_title . ": " . $b_filename . '">';
                $videoLink .= '<video id="video-' . $b_seq. '" title="' . $record_title . ": " . $b_filename . '" ';
                $videoLink .= 'controls preload="none" width="600">';
                $videoLink .= '<source src="' . $b_uri . '" type="video/webm" />Video loading...';
                $videoLink .= '</video>';
                $videoLink .= '</div>';

                $videoFile = true;

            }
        }
        else if ((strpos($b_uri, ".pdf") > 0) or (strpos($b_uri, ".PDF") > 0)) {

            $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
            $bitstreamUri = $this->skylight_utilities->getBitstreamUri($bitstream);

            $pdfLink .= 'Click ' . $bitstreamLink . 'to download. (<span class="bitstream_size">' . getBitstreamSize($bitstream) . '</span>)';
        }

        ?>
        <?php
    }

}
?>

<div class="container">
    <div class="content">

    <div class="page-header">
        <h1 class="itemtitle hidden-sm hidden-xs"><?php echo $record_title; ?></h1>
        <h4 class="itemtitle hidden-lg hidden-md"><?php echo $record_title; ?></h4>
        <div class="piccolo"><?php if(isset($solr[$piccolo_field][0])) {
                echo $solr[$piccolo_field][0];
            } ?></div>
    </div>

    <?php if($mainImageTest === true) { ?>

            <div class="col-md-8 hidden-sm hidden-xs full-image ">
                <?php echo $bitstreamLink; ?>
            </div>
            <div class="col-sm-8 hidden-lg hidden-md resized-image">
                <?php echo $bitstreamLink; ?>
            </div>
    <?php } ?>
        <div class="col-sm-4 hidden-xs hidden-sm metadata">
            <dl class="dl-horizontal">
                <?php foreach($recorddisplay as $key) {

                    $element = $this->skylight_utilities->getField($key);

                    if(isset($solr[$element])) {

                        echo '<dt>' . $key . '</dt>';

                        echo '<dd>';
                        foreach($solr[$element] as $index => $metadatavalue) {
                            // if it's a facet search
                            // make it a clickable search link
                            if(in_array($key, $filters)) {

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
                        echo '</dd>';
                    }
                } ?>
            </dl>
        </div> <!-- metadata -->
    <div class="col-xs-9 hidden-lg hidden-md  metadata">
        <dl class="dl-horizontal">
            <?php foreach($recorddisplay as $key) {

                $element = $this->skylight_utilities->getField($key);

                if(isset($solr[$element])) {

                    echo '<dt>' . $key . '</dt>';

                    echo '<dd>';
                    foreach($solr[$element] as $index => $metadatavalue) {
                        // if it's a facet search
                        // make it a clickable search link
                        if(in_array($key, $filters)) {

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
                    echo '</dd>';
                }
            } ?>
        </dl>
    </div> <!-- metadata -->

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 record-tabs">
            <ul id="tabs" class="nav nav-tabs  nav-justified" data-tabs="tabs">
                <li class="active"><a data-toggle="tab" href="#description"><i class="fa fa-file-text fa-lg">&nbsp;</i>Description</a></li>
                <li><a data-toggle="tab" href="#maker"><i class="fa fa-industry fa-lg">&nbsp;</i>Maker</a></li>
                <li><a data-toggle="tab" href="#gallery"><i class="fa fa-image fa-lg">&nbsp;</i>Gallery</a></li>
                <li><a data-toggle="tab" href="#video"><i class="fa fa-video-camera  fa-lg">&nbsp;</i>Video</a></li>
                <li><a data-toggle="tab" href="#audio"><i class="fa fa-music fa-lg">&nbsp;</i>Audio</a></li>
                <!--<li><a data-toggle="tab" href="#related"><i class="fa fa-plus-circle fa-lg">&nbsp;</i>Related</a></li>-->
            </ul>
            <div class="tab-content">
                <div id="description" class="tab-pane fade in active">
                    <?php include('description.php');?>
                </div>
                <div id="maker" class="tab-pane fade">
                    <?php include('creator.php');?>
                </div>
                <div id="gallery" class="tab-pane fade">
                    <?php include('gallery.php');?>
                </div>
                <div id="video" class="tab-pane fade">
                    <?php include('video.php');?>
                </div>
                <div id="audio" class="tab-pane fade">
                    <?php include('audio.php');?>
                </div>
                <!--<div id="related" class="tab-pane fade--">
                    <!--?php include('related_items.php');?>
                </div>-->
            </div>
        </div>
    </div>
    <a href="#" title="Back to Search Results" class="btn btn-default" onClick="history.go(-1);"><i class="fa fa-arrow-left">&nbsp;</i>Back to Search Results</a>

<!--</div> <!-- container -->
