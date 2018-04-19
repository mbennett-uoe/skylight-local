<div class="container">

    <?php
    $bitstream_field = $this->skylight_utilities->getField("Bitstream");
    $thumbnail_field = $this->skylight_utilities->getField("Thumbnail");
    $parent_collection_field = $this->skylight_utilities->getField("Parent Collection");
    $child_collection_field = $this->skylight_utilities->getField("Sub Collections");
    $internal_uri_field = $this->skylight_utilities->getField("Internal URI");
    $handle_prefix = $this->config->item('skylight_handle_prefix');
    $filters = array_keys($this->config->item("skylight_filters"));
    $media_uri = $this->config->item("skylight_media_url_prefix");
    $aspace_uri_field = $this->skylight_utilities->getField("ASpace URI");
    $luna_uri_field= $this->skylight_utilities->getField("LUNA URI");
    $lms_uri_field = $this->skylight_utilities->getField("LMS URI");
    $other_uri_field = $this->skylight_utilities->getField("Other URI");
    ?>

    <h1 class="itemtitle"><?php echo $record_title ?></h1>


    <div class="content">

        <table class="table table-hover">
            <tbody>

            <?php
            foreach ($recorddisplay as $key) {
                $element = $this->skylight_utilities->getField($key);
                if (isset($solr[$element])) {
                    echo '<tr><th>' . $key . '</th><td>';
                    foreach ($solr[$element] as $index => $metadatavalue) {
                        // if it's a facet search
                        // make it a clickable search link
                        if (in_array($key, $filters)) {
                            $orig_filter = urlencode($metadatavalue);
                            $lower_orig_filter = strtolower($metadatavalue);
                            $lower_orig_filter = urlencode($lower_orig_filter);
                            echo '<a href="./search/*:*/' . $key . ':%22' . $lower_orig_filter . '%7C%7C%7C' . $orig_filter . '%22">' . $metadatavalue . '</a>';
                        } else {
                            echo $metadatavalue;
                        }
                        if ($index < sizeof($solr[$element]) - 1) {
                            echo '; ';
                        }
                    }
                    echo '</td></tr>';
                }
            }
            if (isset($solr[$parent_collection_field])) {
                echo '<tr><th>Parent Collection</th><td>';
                foreach ($solr[$parent_collection_field] as $parent) {
                    $find = 'http://hdl.handle.net';
                    $pos = strpos($parent, $find);
                    if ($pos !== false) {
                        $parents = explode("|", $parent);
                        //todo move into config
                        $parent_link = str_replace("http://hdl.handle.net/" . $handle_prefix . "/", "./record/", $parents[0]);
                        $parent_name = (isset($parents[1]) ? $parents[1] : "Parent Collection");
                        echo '<a href="' . $parent_link . '">' . $parent_name . '</a>';
                    } else {
                        echo $parent;
                    }
                    if ($index < sizeof($solr[$parent_collection_field]) - 1) {
                        echo '; ';
                    }
                }
                echo '</td></tr>';
            }
            if (isset($solr[$child_collection_field])) {
                echo '<tr><th>Sub Collections</th><td>';
                foreach ($solr[$child_collection_field] as $child) {
                    $find = 'http://hdl.handle.net';
                    $pos = strpos($child, $find);
                    if ($pos !== false) {
                        $children = explode("|", $child);
                        //todo move into config
                        $link = str_replace("http://hdl.handle.net/" . $handle_prefix . "/", "./record/", $children[0]);
                        $name = $children[1];
                        echo '<a href="' . $link . '">' . $name . '</a>';
                    } else {
                        echo $child;
                    }
                    if ($index < sizeof($solr[$child_collection_field]) - 1) {
                        echo '; ';
                    }
                }
                echo '</td></tr>';
            }
            ?>
            </tbody>
        </table>

        <?php
        if (isset($solr[$bitstream_field]) && $link_bitstream) {
        $numThumbnails = 0;
        $mainImage = false;
        $videoFile = false;
        $audioFile = false;
        $pdfFile = false;
        $audioLink = "";
        $videoLink = "";
        $pdfLink = "";
        $bitstream_array = array();
        foreach ($solr[$bitstream_field] as $bitstream) {
            $mp4ok = false;
            $b_segments = explode("##", $bitstream);
            $b_filename = $b_segments[1];
            $b_handle = $b_segments[3];
            $b_seq = $b_segments[4];
            $b_handle_id = preg_replace('/^.*\//', '', $b_handle);
            $b_uri = './record/' . $b_handle_id . '/' . $b_seq . '/' . $b_filename;
            if ((strpos($b_filename, ".jpg") > 0) || (strpos($b_filename, ".JPG") > 0)) {
                $bitstream_array[$b_seq] = $bitstream;
            } else if ((strpos($b_uri, ".mp3") > 0) or (strpos($b_uri, ".MP3") > 0)) {
                $audioLink .= '<audio controls>';
                $audioLink .= '<source src="' . $b_uri . '" type="audio/mpeg" />Audio loading...';
                $audioLink .= '</audio>';
                $audioFile = true;
            } else if ((strpos($b_filename, ".mp4") > 0) or (strpos($b_filename, ".MP4") > 0)) {
                $b_uri = $media_uri . $b_handle_id . '/' . $b_seq . '/' . $b_filename;
                // Use MP4 for all browsers other than Chrome
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == false) {
                    $mp4ok = true;
                } //Microsoft Edge is calling itself Chrome, Mozilla and Safari, as well as Edge, so we need to deal with that.
                else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') == true) {
                    $mp4ok = true;
                }
                if ($mp4ok == true) {
                    $videoLink .= '<div class="flowplayer" data-analytics="' . $ga_code . '" title="' . $record_title . ": " . $b_filename . '">';
                    $videoLink .= '<video preload=auto loop width="100%" height="auto" controls preload="true" width="660">';
                    $videoLink .= '<source src="' . $b_uri . '" type="video/mp4" />Video loading...';
                    $videoLink .= '</video>';
                    $videoLink .= '</div>';
                    $videoFile = true;
                }
            } else if ((strpos($b_filename, ".webm") > 0) or (strpos($b_filename, ".WEBM") > 0)) {
                //Microsoft Edge needs to be dealt with. Chrome calls itself Safari too, but that doesn't matter.
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') == false) {
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == true) {
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
            } else if ((strpos($b_uri, ".pdf") > 0) or (strpos($b_uri, ".PDF") > 0)) {
                $bitstreamLink = $this->skylight_utilities->getBitstreamLink($bitstream);
                $bitstreamUri = $this->skylight_utilities->getBitstreamUri($bitstream);
                $pdfLink .= 'Click ' . $bitstreamLink . ' to download. (<span class="bitstream_size">' . getBitstreamSize($bitstream) . '</span>)';
                $pdfFile = true;
            }
        }
        if (count($bitstream_array) > 0) {
            // sorting array so main image is first
            ksort($bitstream_array);
        }
        $b_seq = "";
        foreach ($bitstream_array as $bitstream) {
            $b_segments = explode("##", $bitstream);
            $b_filename = $b_segments[1];
            $b_handle = $b_segments[3];
            $b_seq = $b_segments[4];
            $b_handle_id = preg_replace('/^.*\//', '', $b_handle);
            $b_uri = './record/' . $b_handle_id . '/' . $b_seq . '/' . $b_filename;
            // is there a main image
            if (!$mainImage) {
                $bitstreamLink = '<div class="main-image">';
                $bitstreamLink .= '<a title = "' . $record_title . ' ' . $b_filename . '" class="fancybox" rel="group" href="' . $b_uri . '"> ';
                $bitstreamLink .= '<img class="responsive" src = "' . $b_uri . '">';
                $bitstreamLink .= '</a>';
                $bitstreamLink .= '</div>';
                $mainImage = true;
            } // we need to display a thumbnail
            else {
                // if there are thumbnails
                if (isset($solr[$thumbnail_field])) {
                    foreach ($solr[$thumbnail_field] as $thumbnail) {
                        $t_segments = explode("##", $thumbnail);
                        $t_filename = $t_segments[1];
                        if ($t_filename === $b_filename . ".jpg") {
                            $t_handle = $t_segments[3];
                            $t_seq = $t_segments[4];
                            $t_uri = './record/' . $b_handle_id . '/' . $t_seq . '/' . $t_filename;
                            $thumbnailLink[$numThumbnails] = '<div class="thumbnail-tile';
                            $thumbnailLink[$numThumbnails] .= '"><a title = "' . $record_title . '" class="fancybox" rel="group" href="' . $b_uri . '"> ';
                            $thumbnailLink[$numThumbnails] .= '<img src = "' . $t_uri . '" class="record-thumbnail" title="' . $record_title . ' ' . $t_filename . '" /></a></div>';
                            $numThumbnails++;
                        }
                    }
                }
            }
        } // end for each bitstream
        if ($pdfFile) {
            echo '<tr><th>Supporting Document: </th><td>' . $pdfLink . '</td></tr>';
        }
        ?>

        <div class="record_bitstreams">
            <?php
            if ($mainImage) {?>
                <div class="main-image">
                    <div id="openseadragon" style="width:1200px;height:450px;">
                        <script src="<?php echo base_url()?>assets/openseadragon/openseadragon.min.js"></script>
                        <script type="text/javascript">
                            OpenSeadragon({
                                id:            "openseadragon",
                                prefixUrl: "<?php echo base_url()?>assets/openseadragon/images/",
                                preserveViewport: false,
                                visibilityRatio: 1,
                                minZoomLevel: 0,
                                defaultZoomLevel: 1,
                                panHorizontal: true,
                                sequenceMode: true,
                                tileSources:  [{
                                    //"height": 600,
                                    // tileSize:600,
                                    type: 'image',
                                    url:  '<?php echo $b_uri ; ?>'
                                }]
                            });
                        </script>
                    </div>
                </div>
                <?php


                // echo $bitstreamLink;
                echo '<div class="clearfix"></div>';
            }
            $i = 0;
            $newStrip = false;
            if ($numThumbnails > 0) {
                echo '<div class="thumbnail-strip">';
                foreach ($thumbnailLink as $thumb) {
                    echo $thumb;
                }
                echo '</div><div class="clearfix"></div>';
            }
            if ($audioFile) {
                echo '<br>.<br>' . $audioLink;
            }
            if ($videoFile) {
                echo '<br>.<br>' . $videoLink;
            }
            echo '</div><div class="clearfix"></div>';
            }// end if there are bitstreams
            echo '</div>';
            ?>
            <?php
            if (isset($solr[$internal_uri_field])) {
                foreach($solr[$internal_uri_field] as $internalURI) {
                    $internalURI = str_replace('"', '%22', $internalURI);
                    $internalURI = str_replace('|', '%7C', $internalURI);
                    echo '<p class="collection-link"><a href="'. $internalURI . '" target="_blank">View the items in the collection <i class="fa fa-external-link">&nbsp;</i> </a></p>';
                }
            }
            else if (isset($solr[$other_uri_field])) {
                foreach($solr[$other_uri_field] as $otherURI) {

                        echo '<p class="collection-link"><a href="' . $otherURI . '" target="_blank">View the resource externally  <i class="fa fa-external-link">&nbsp;</i> </a></p>';

                }
            }
            else if (isset($solr[$aspace_uri_field])) {
                foreach($solr[$aspace_uri_field] as $aspaceURI) {


                    $separator = ' || ';
                    if (strpos($aspaceURI,$separator) !== false) {
                        $archivesarray = explode($separator,$aspaceURI);
                        echo '<p class="collection-link"><a href="' . $archivesarray[0] . '" target="_blank">View ' . ucwords($archivesarray[1]). ' in the University Archives <i class="fa fa-external-link">&nbsp;</i> </a></p>';

                    }
                    else
                    {
                        echo '<p class="collection-link"><a href="' . $aspaceURI . '" target="_blank">View the items in the collection <i class="fa fa-external-link">&nbsp;</i> </a></p>';

                    }
                }
            }
            else if (isset($solr[$luna_uri_field])) {
                foreach ($solr[$luna_uri_field] as $lunaURI) {
                    echo '<p class="collection-link"><a href="' . $lunaURI . '" target="_blank">View the items in the collection <i class="fa fa-external-link">&nbsp;</i> </a></p>';
                }
            }
            else if (isset($solr[$lms_uri_field])) {
                foreach ($solr[$lms_uri_field] as $lmsURI) {
                    echo '<p class="collection-link"><a href="' . $lmsURI . '" target="_blank">View the items in the collection <i class="fa fa-external-link">&nbsp;</i> </a></p>';
                }
            }
            ?>
            <button type="button" class="btn btn-custom" onClick="history.go(-1);">Back to Search Results</button>
        </div>


