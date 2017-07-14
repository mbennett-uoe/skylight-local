
<div class="randoms">
    <?php

    $title_field = $this->skylight_utilities->getField('Title');
    $author_field = $this->skylight_utilities->getField('Author');
    $subject_field = $this->skylight_utilities->getField('Subject');
    $abstract_field = $this->skylight_utilities->getField('Abstract');
    $date_field = $this->skylight_utilities->getField('Date');
    $type_field = $this->skylight_utilities->getField("Type");
    $bitstream_field = $this->skylight_utilities->getField('Bitstream');
    $thumbnail_field = $this->skylight_utilities->getField('Thumbnail');

    //pop the first item from the list

    // $first_doc = array_shift($randomitems);


    ?>

    <div class="container-random">

        <?php

        $k = 0;
        foreach ($randomitems as $index => $doc) {

            $extraclass = ($k % 4 == 0) ? "thumbnail-first" : ""; ?>

            <div class="thumbnail random-thumbnail <?php echo $extraclass; ?>">
                <div class="random-title"><a href="./record/<?php echo $doc['id']?>"><?php echo $doc[$title_field][0]; ?></a></div>

                <?php

                if(isset($doc[$bitstream_field])) {

                    $i = 0;
                    $started = false;
                    // loop through to get min sequence
                    foreach ($doc[$bitstream_field] as $bitstream)
                    {
                        $b_segments = explode("##", $bitstream);
                        $b_filename = $b_segments[1];
                        $b_seq = $b_segments[4];

                        if((strpos($b_filename, ".jpg") > 0) || (strpos($b_filename, ".JPG") > 0)) {

                            $bitstream_array[$b_seq] = $bitstream;

                            if ($started) {
                                if ($b_seq < $min_seq) {
                                    $min_seq = $b_seq;
                                }
                            }
                            else {
                                $min_seq = $b_seq;
                                $started = true;
                            }
                        }
                        $i++;
                    }

                    // if there is a thumbnail and a bitstream
                    if(isset($min_seq) && count($bitstream_array) > 0) {

                        // get all the information
                        $b_segments = explode("##", $bitstream_array[$min_seq]);
                        $b_filename = $b_segments[1];
                        $b_handle = $b_segments[3];
                        $b_seq = $b_segments[4];
                        $b_handle_id = preg_replace('/^.*\//', '',$b_handle);
                        $b_uri = './record/'.$b_handle_id.'/'.$b_seq.'/'.$b_filename;
                        $thumbnailLink = "";

                        if(isset($doc[$thumbnail_field])) {
                            foreach ($doc[$thumbnail_field] as $thumbnail) {

                                $t_segments = explode("##", $thumbnail);
                                $t_filename = $t_segments[1];

                                if ($t_filename === $b_filename . ".jpg") {

                                    $t_handle = $t_segments[3];
                                    $t_seq = $t_segments[4];
                                    $t_uri = './record/'.$b_handle_id.'/'.$t_seq.'/'.$t_filename;

                                    $thumbnailLink = '<div class="random-image">';
                                    $thumbnailLink .= '<a class="random-image-link" href="./record/'.$doc['id'].'" title="' . $doc[$title_field][0] . '"> ';
                                    $thumbnailLink .= '<img src="'.$t_uri.'" class="random-thumbnailimg" title="'. $doc[$title_field][0] .'" /></a></div>';
                                }
                            }
                        }
                        // there isn't a thumbnail so display the bitstream itself
                        else {

                            $thumbnailLink = '<div class="random-image">';
                            $thumbnailLink .= '<a class="random-image-link" href="./record/'.$doc['id'].'" title="' . $doc[$title_field][0] . '"> ';
                            $thumbnailLink .= '<img src="'.$b_uri.'" class="random-thumbnailimg" title="'. $doc[$title_field][0] .'" /></a></div>';
                        }

                        echo $thumbnailLink;
                    }

                } //end if there are bitstreams ?>


            </div>

            <?php $k++;

        } ?>

    </div>
    <div>
        <p>
            <a target="_blank" href="http://images.is.ed.ac.uk/luna/servlet/iiif/collection/g/376">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/International_Image_Interoperability_Framework_logo.png" class="iiiflogo" title="Right-click, Copy Link to get the full IIIF manifest for the collection."></a>
            <a target="_blank" href="http://images.is.ed.ac.uk/luna/servlet/iiif/collection/g/376">
                <img src="http://images.is.ed.ac.uk/luna/images/LUNAIIIF80.png" class="lunaiiif" title="Right-click, Copy Link to get the full IIIF manifest for the collection."></a>
            This collection is IIIF-compliant. <a href ="./iiif">See more</a>.
        </p>
    </div>

<div class="clearfix"></div>
