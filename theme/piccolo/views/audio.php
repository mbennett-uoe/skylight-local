<div class="record_bitstreams">
    <?php
    if(isset($solr[$bitstream_field]) && $link_bitstream) {
        if($audioFile) {
            echo $audioLink;
        }
        else
        {
            echo 'There are no sound recordings';
        }
    }

    ?>
</div>
