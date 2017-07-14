<?php

//Fast access to important variables
$id = $this->skylight_utilities->getField("ID");
$title = $this->skylight_utilities->getField("Title");
$coverImageName = $this->skylight_utilities->getField("Image Name");
$location = $this->skylight_utilities->getField("Spatial Coverage");

$title = isset( $doc[$title_field][0] ) ? $doc[$title_field][0] : "Untitled";
$image_name = isset( $solr[$coverImageName][0] ) ? $solr[$coverImageName][0] : "missing.jpg";
//Image variables setup
$coverImageJSON = "http://test.cantaloupe.is.ed.ac.uk/iiif/2/" . $image_name;
$coverImageURL = $coverImageJSON . '/full/full/0/default.jpg';
$coverImage = '<img class="record-image" src ="' .$coverImageURL .'"/>';

$json =  file_get_contents($coverImageJSON);
$jobj = json_decode($json, true);
$error = json_last_error();
$jsonheight = $jobj['height'];
$jsonwidth = $jobj['width'];

?>

<!--Seadragon image viewer-->
<div id="toolbarDiv" class="toolbar" style="position: relative;">
    <div style="background: transparent none repeat scroll 0% 0%; border: medium none; margin: 0px; padding: 0px; position: static; width: 100%; height: 100%;">
        <div style="background: transparent none repeat scroll 0% 0%; border: medium none; margin: 0px; padding: 0px; position: absolute; left: 0px; top: 0px;"></div>
        <div style="background: transparent none repeat scroll 0% 0%; border: medium none; margin: 0px; padding: 0px; position: absolute; right: 0px; top: 0px;"></div>
        <div style="background: transparent none repeat scroll 0% 0%; border: medium none; margin: 0px; padding: 0px; position: absolute; right: 0px; bottom: 0px;"></div>
        <div style="background: transparent none repeat scroll 0% 0%; border: medium none; margin: 0px; padding: 0px; position: absolute; left: 0px; bottom: 0px;"></div>
    </div>
</div>

<div id="openseadragon" class="cover-image-container full-width">
</div>


<!--Page-specific script to load the record image-->
<script>
    var imageURL = <?php echo json_encode($coverImageJSON); ?>;
    var imageHeight = <?php echo json_encode($jsonheight); ?>;
    var imageWidth = <?php echo json_encode($jsonwidth); ?>;
</script>
<script src="<?php echo base_url(); ?>theme/<?php echo $this->config->item('skylight_theme'); ?>/js/openseadragon.min.js"></script>
<script src="<?php echo base_url(); ?>theme/<?php echo $this->config->item('skylight_theme'); ?>/js/openseadragonconfig.js"></script>

<!--Record information-->
<div class="record-info">
    <h1 class="itemtitle">
        <div class="backbtn">
            <i class="fa fa-arrow-left" aria-hidden="true" type="button" value="Back to Search Results" onClick="history.go(-1);"></i>
        </div>
        <?php echo $title ?>
    </h1>
    <div class="description">
        <?php
            foreach($recorddisplay as $key) {
                $element = $this->skylight_utilities->getField($key);
                if(isset( $solr[$element][0] )) {
                    echo '<div class="row"><span class="field">' . $key . '</span>' . $solr[$element][0] . '</div>';
                }
            }
        ?>
        <div id="map">

            <script>
                $(window).bind("load", function() {
                    initMap(); addLocation("<?php echo $solr[$location][0] ?>");
                });
            </script>
        </div>
        <i class="fa fa-angle-double-down hidden-xs hidden-sm" aria-hidden="true"></i>
    </div>
</div>

<div class="content hidden">



