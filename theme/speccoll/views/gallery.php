<div class="record_bitstreams row">
<?php if(isset($solr[$bitstream_field]) && $link_bitstream) {
    if ($numThumbnails > 0) {
        foreach ($thumbnailLink as $thumb) {
            echo '<div class="col-xs-3 col-sm-3 ">';
            echo '<div class="thumbnail">'.$thumb .'</div>';
            echo '</div>';
        }
    }
    else{
        echo 'There are no further images.';
    }
}?>
</div>


<div class="clearfix"></div>