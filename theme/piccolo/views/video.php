<div class="record_bitstreams">
    <?php
    if(isset($solr[$bitstream_field]) && $link_bitstream) {
        if($videoFile) {

            echo $videoLink;
        }
        else
        {
            echo 'There are no videos';
        }
    }

    ?>
</div>
