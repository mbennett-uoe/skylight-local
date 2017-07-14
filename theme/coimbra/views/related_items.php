
<div class="row related inactive container">
    <h4>Related Items</h4>
        <?php
        $numrel = count($related_items);
        // if there are related items
        if($numrel > 4) { ?>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    <?php
                    $type_field = $this->skylight_utilities->getField('Type');
                    $i = 0;
                    foreach ($related_items as $index => $doc) {
                        $type = 'Unknown';
                        if(isset($doc[$type_field])) {
                            $type = "media-" . strtolower(str_replace(' ','-',$doc[$type_field][0]));
                        }
                        ?>
                        <div class="item <?php if ($i == 0) { echo 'active';}?>">
                            <div class="col-xs-3">

                                <div class="">
                                    <?php $bitstream_array = array();

                                    if(isset($doc[$bitstream_field])) {


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

                                                        $thumbnailLink = '<a href="./record/'.$doc['id'] .'" title = "' . $doc[$title_field][0] . '" > ';
                                                        $thumbnailLink .= '<img src = "'.$t_uri.'" class="search-thumbnail" title="'. $doc[$title_field][0] .'" /></a>';
                                                    }
                                                }
                                            }
                                            // there isn't a thumbnail so display the bitstream itself
                                            else {
                                                $thumbnailLink = '<a href="./record/'.$doc['id'] .'" title = "' . $doc[$title_field][0] . '"> ';
                                                $thumbnailLink .= '<img src = "'.$b_uri.'" class="search-thumbnail" title="'. $doc[$title_field][0] .'" /></a>';
                                            }

                                            echo $thumbnailLink;
                                        }
                                    } //end if there are bitstreams ?>
                                    <p class="text-center hidden-xs">
                                        <a href="./record/<?php echo $doc['id']?>"><?php echo $doc[$title_field][0]; ?></a>
                                    </p>
                                    <p class="text-center hidden-md hidden-sm hidden-lg">
                                        <small><a href="./record/<?php echo $doc['id']?>"><?php echo $doc[$title_field][0]; ?></a></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }?>
                </div>
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left text-primary" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right text-primary" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>
            <?php
        }
        elseif($numrel > 0 &&  $numrel < 4)  { ?>
                    <?php
                    $type_field = $this->skylight_utilities->getField('Type');
                    $i = 0;
                    foreach ($related_items as $index => $doc) {
                        $type = 'Unknown';
                        if(isset($doc[$type_field])) {
                            $type = "media-" . strtolower(str_replace(' ','-',$doc[$type_field][0]));
                        }
                        ?>
                        <div class="item<?php if ($i == 0) { echo ' active';}?>">
                            <div class="col-lg-3 col-xs-<?php echo (12/$numrel)?>">

                                <div class="related-image">
                                    <?php $bitstream_array = array();

                                    if(isset($doc[$bitstream_field])) {

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

                                                        $thumbnailLink = '<a href="./record/'.$doc['id'] .'" title = "' . $doc[$title_field][0] . '" > ';
                                                        $thumbnailLink .= '<img src = "'.$t_uri.'" class="related-thumbnail hidden-xs" title="'. $doc[$title_field][0] .'" /></a>';
                                                        $thumbnailLink .= '<img src = "'.$t_uri.'" class="related-thumbnail related-xs hidden-lg hidden-md hidden-sm" title="'. $doc[$title_field][0] .'" /></a>';
                                                    }
                                                }
                                            }
                                            // there isn't a thumbnail so display the bitstream itself
                                            else {
                                                $thumbnailLink = '<a href="./record/'.$doc['id'] .'" title = "' . $doc[$title_field][0] . '"> ';
                                                $thumbnailLink .= '<img src = "'.$b_uri.'" class="related-thumbnail hidden-xs" title="'. $doc[$title_field][0] .'" /></a>';
                                                $thumbnailLink .= '<img src = "'.$b_uri.'" class="related-thumbnail related-xs hidden-lg hidden-md hidden-sm" title="'. $doc[$title_field][0] .'" /></a>';
                                            }

                                            echo $thumbnailLink;
                                        }
                                    } //end if there are bitstreams ?>
                                    <p class="text-center hidden-xs">
                                        <a href="./record/<?php echo $doc['id']?>"><?php echo $doc[$title_field][0]; ?></a>
                                    </p>
                                    <p class="text-center hidden-md hidden-sm hidden-lg">
                                        <small><a href="./record/<?php echo $doc['id']?>"><?php echo $doc[$title_field][0]; ?></a></small>
                                    </p>
                                </div>
                            </div>





                        </div>
                        <?php
                        $i++;
                    }?>
            </div>

</div>
        <?php }
        // else there aren't any related items
        else { ?>

            None.
            <div class="spacer"></div>

</div>
        <?php }?>

        <script type='text/javascript'>

            $('#myCarousel').carousel({
                interval: 10000
            })

            $('.carousel .item').each(function(){
                var next = $(this).next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));

                for (var i=0;i<2;i++) {
                    next=next.next();
                    if (!next.length) {
                        next = $(this).siblings(':first');
                    }

                    next.children(':first-child').clone().appendTo($(this));
                }
            });;
        </script>
</div>