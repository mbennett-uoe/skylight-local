<?php

//Fast access to important variables
$id = $this->skylight_utilities->getField("ID");
$title = $this->skylight_utilities->getField("Title");
$coverImageName = $this->skylight_utilities->getField("Image Name");
$location = $this->skylight_utilities->getField("Spatial Coverage");


////Image variables setup
//$coverImageJSON = "http://test.cantaloupe.is.ed.ac.uk/iiif/2/" . $solr[$coverImageName][0];
//$coverImageURL = $coverImageJSON . '/full/full/0/default.jpg';
//$coverImage = '<img class="record-image" src ="' .$coverImageURL .'"/>';

//Image variables setup
$imageNames = ['1.jpg', '2.jpg', '3.jpeg', '4.jpg'];
echo '<script>var imageSource = [];</script>';
for($i=0;$i<4;$i++){
    $coverImageJSON = "http://127.0.0.1:8182/iiif/2/" . $imageNames[$i];
    $coverImageURL = $coverImageJSON . '/full/full/0/default.jpg';
    $coverImage = '<img class="record-image" src ="' .$coverImageURL .'"/>';

    $json =  file_get_contents($coverImageJSON);
    $jobj = json_decode($json, true);
    $error = json_last_error();
    $jsonheight = $jobj['height'];
    $jsonwidth = $jobj['width'];
    echo '
    <script>
    imageSource[' . $i . '] = {
        "@context": "http://iiif.io/api/image/2/context.json",
            "@id": "' . $coverImageJSON . '",
            "height": ' . $jsonheight . ',
            "width": ' . $jsonwidth . ',
            "profile": ["http://iiif.io/api/image/2/level2.json",
                {
                    "formats": ["jpg"]
                }
            ],
            "protocol": "http://iiif.io/api/image",
            "tiles": [{
            "scaleFactors": [1, 2, 8, 16, 32],
                "width": "750",
                "height": "750"
            }],
            "tileSize": 750
        };
        </script>';
}

?>


<script src="<?php echo base_url(); ?>theme/<?php echo $this->config->item('skylight_theme'); ?>/js/scrollify.js"></script>
<script>
    $(function() {
        if(!(/Android|webOS|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent) )) {
            $.scrollify({
                section: ".scroll",
                offset: -50,
                updateHash: false,
                standardScrollElements: "#openseadragon, .record-info",
                interstitialSection: ".footer"
            });
        }
    });
</script>
<section class="image-view full-height-section scroll">

    <!--Seadragon image viewer-->
    <div id="toolbarDiv" class="toolbar">
        <h2 id="previous-pic"></h2>
        <h2 id="next-pic"></h2>
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




    <h3 class="more-info" onclick="$.scrollify.next();">&#x1D55A;</h3>
</section>
<section class="section-divisor">

</section>

<section class="info-view full-height-section scroll">
    <div class="record-info col-xs-12 col-md-5 col-md-offset-1">
        <h2 class="itemtitle">
            <?php echo $solr[$title][0] ?>
        </h2>
        <div class="description">
            <?php
            foreach($recorddisplay as $key) {
                $element = $this->skylight_utilities->getField($key);
                if(isset( $solr[$element][0] )) {
                    echo '<div class="row"><span class="field">' . $key . '</span>' . $solr[$element][0] . '</div>';
                }
            }
            ?>
        </div>
    </div>
    <script>
        (function($){
            $(window).on("load",function(){
                $(".record-info").mCustomScrollbar({
                    theme: "light-thick",
                    scrollInertia: 100,
                    mouseWheel:{ preventDefault: true}
                    });
            });
        })(jQuery);
    </script>
    <div id="map" class="col-md-5 col-md-offset-1">
        <script>
            $(window).bind("load", function() {
                initMap(); addLocation("<?php echo $solr[$location][0] ?>");
            });
        </script>
    </div>
    <h4 class="back-to-search" value="Back to Search Results" onClick="history.go(-1);">Back to search</h4>
</section>
<div class="content hidden">



