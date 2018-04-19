<?php

$author_field = $this->skylight_utilities->getField("Author");
$maker_field = $this->skylight_utilities->getField("Maker");
$type_field = $this->skylight_utilities->getField("Type");
$bitstream_field = $this->skylight_utilities->getField("Bitstream");
$thumbnail_field = $this->skylight_utilities->getField("Thumbnail");
$filters = array_keys($this->config->item("skylight_filters"));
$link_uri_field = $this->skylight_utilities->getField("Link");
$short_field = $this->skylight_utilities->getField("Short Description");
$date_field = $this->skylight_utilities->getField("Date");
$media_uri = $this->config->item("skylight_media_url_prefix");

$type = 'Unknown';
$mainImageTest = false;
$numThumbnails = 0;
$bitstreamLinks = array();
$image_id = "";

// booleans for video/audio
$mainImage = false;
$videoFile = false;
$audioFile = false;


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

                            $thumbnailLink[$numThumbnails] = '<a title = "' . $record_title . '" class="fancybox" rel="group" href="' . $b_uri . '"> ';
                            $thumbnailLink[$numThumbnails] .= '<img src = "'.$t_uri.'" title="'. $record_title .'" /></a>';

                            $numThumbnails++;
                        }
                    }
                }
            }
        }
        else if ((strpos($b_uri, ".mp3") > 0) or (strpos($b_uri, ".MP3") > 0)) {

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
        else if ((strpos($b_uri, ".pdf") > 0) or (strpos($b_uri, ".PDF") > 0)) {

            $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
            $bitstreamUri = $this->skylight_utilities->getBitstreamUri($bitstream);
            $pdfLink .= 'Click ' . $bitstreamLink . 'to download. (<span class="bitstream_size">' . getBitstreamSize($bitstream) . '</span>)';
        }
    }
}
?>

<div class="row container">
    <div class="content">

        <div class="page-header">
            <h1 class="itemtitle hidden-sm hidden-xs"><?php echo $record_title; ?></h1>
            <h4 class="itemtitle hidden-lg hidden-md"><?php echo $record_title; ?></h4>
        </div>

        <?php if($mainImageTest === true) { ?>

                <div class="col-md-6 hidden-sm hidden-xs full-image ">
                    <?php echo $bitstreamLink; ?>
                    <br />
                    <a title="Back to Search Results" class="btn btn-default" onClick="history.go(-1);"><i class="fa fa-arrow-left">&nbsp;</i>Back to Search Results</a>

                </div>
                <div class="col-sm-6 hidden-lg hidden-md resized-image">
                    <?php echo str_replace("group", "group-small", $bitstreamLink); ?>
                    <br />
                    <a title="Back to Search Results" class="btn btn-default" onClick="history.go(-1);"><i class="fa fa-arrow-left">&nbsp;</i>Back to Search Results</a>

                </div>
        <?php } ?>
        <div class="col-sm-6 col-xs-12 metadata">
            <?php if(isset($solr[$short_field][0])) {
                echo '<p>' . $solr[$short_field][0] . '</p>';
            }
            ?>

            <div class="record-tabs ">
                <ul id="tabs" class="hidden-xs nav nav-tabs  nav-justified" data-tabs="tabs">
                    <li class="active"><a data-toggle="tab" href="#about"><i class="fa fa-list fa-1x"></i></span><br/>About</a></li>
                    <li><a data-toggle="tab" href="#gallery" title="Gallery"><i class="fa fa-image fa-1x"></i><br/>Gallery</a></li>
                    <?php if($videoFile > 0) { ?>
                        <li><a data-toggle="tab" href="#video" title="Videos"><i class="fa fa-video-camera fa-1x"></i><br/>Video</a></li>
                    <?php } else { ?>
                        <li><a data-toggle="tab" href="#video" title="Videos" class="inactive"><i class="fa fa-video-camera fa-inactive fa-1x"></i><br/>Video</a></li>
                    <?php } ?>
                    <?php if($audioFile) { ?>
                        <li><a data-toggle="tab" href="#audio" title="Audio"><i class="fa fa-music fa-1x"></i><br/>Audio</a></li>
                    <?php } else { ?>
                        <li><a data-toggle="tab" href="#audio" title="Audio" class="inactive"><i class="fa fa-music fa-inactive fa-1x"></i><br/>Audio</a></li>
                    <?php } ?>
                    <li><a data-toggle="tab" href="#maker" title="Marker Information"><i class="fa fa-industry fa-1x"></i><br/>Maker</a></li>
                    <li><a data-toggle="tab" href="#description" title="Description"><i class="fa fa-file-text fa-1x">&nbsp;</i><br/>Description</a></li>
                </ul>
                <ul id="tabs" class="hidden-sm hidden-md hidden-lg nav nav-tabs  nav-justified" data-tabs="tabs">
                    <li class="active"><a data-toggle="tab" href="#about"><i class="fa fa-list  fa-lg"></i></a></li>
                    <li><a data-toggle="tab" href="#gallery" title="Gallery"><i class="fa fa-image  fa-lg"></i></a></li>
                    <?php if($videoFile > 0) { ?>
                        <li><a data-toggle="tab" href="#video" title="Videos"><i class="fa fa-video-camera fa-1x"></i></a></li>
                    <?php } else { ?>
                        <li><a data-toggle="tab" href="#video" title="Videos" class="inactive"><i class="fa fa-video-camera fa-inactive fa-1x"></i></a></li>
                    <?php } ?>
                    <?php if($audioFile) { ?>
                        <li><a data-toggle="tab" href="#audio" title="Audio"><i class="fa fa-music fa-1x"></i></a></li>
                    <?php } else { ?>
                        <li><a data-toggle="tab" href="#audio" title="Audio" class="inactive"><i class="fa fa-music fa-inactive fa-1x"></i></a></li>
                    <?php } ?>
                    <li><a data-toggle="tab" href="#maker"  title="Marker Information"><i class="fa fa-industry  fa-lg"></i></a></li>
                    <li><a data-toggle="tab" href="#description"  title="Description"><i class="fa fa-file-text  fa-lg"></i></a></li>
                </ul>

                <div class="tab-content">
                    <div id="about" class="tab-pane fade in active">
                        <?php include('record_about.php');?>
                    </div>
                    <div id="gallery" class="tab-pane fade ">
                        <?php include('gallery.php');?>
                    </div>
                    <div id="audio" class="tab-pane fade">
                        <?php include('audio.php');?>
                    </div>
                    <div id="video" class="tab-pane fade">
                        <?php include('video.php');?>
                    </div>
                    <div id="maker" class="tab-pane fade">
                        <?php include('creator.php');?>
                    </div>
                    <div id="description" class="tab-pane fade">
                        <?php include('description.php');?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- content-->
</div> <!-- row container-->