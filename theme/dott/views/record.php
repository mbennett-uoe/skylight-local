<?php

$author_field = $this->skylight_utilities->getField("Creator");
$type_field = $this->skylight_utilities->getField("Type");
$date_field = $this->skylight_utilities->getField("Date");
$parent_id_field = $this->skylight_utilities->getField("Parent_Id");
$parent_type_field = $this->skylight_utilities->getField("Parent_Type");
$id_field = $this->skylight_utilities->getField("Identifier");
$filters = array_keys($this->config->item("skylight_filters"));
$link_uri_field = $this->skylight_utilities->getField("Link");
$id = $this->skylight_utilities->getField("Id");

$link_uri_prefix  = $this->config->item("skylight_link_url");


$mainImageTest = false;
$numThumbnails = 0;
$bitstreamLinks = array();
?>

<div class="col-md-9 col-sm-9 col-xs-12" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <h1 class="itemtitle"><?php echo $record_title ?></h1>
    </div>

    <div class="row">
    <button class="btn btn-info"><a href ="<?php echo $link_uri_prefix ?><?php echo $solr[$id][0] ?>"
                                    title="Full record at archive collections online " target="_blank">
            View full record in University of Edinburgh archives catalogue <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></button>
    </div>
    <div class="row full-metadata">
        <table class="table">
            <tbody>
            <?php $excludes = array("");

            foreach($recorddisplay as $key) {
                $element = $this->skylight_utilities->getField($key);

                if(isset($solr[$element])) {
                    if(!in_array($key, $excludes)) {
                        echo '<tr><th>'.$key.'</th><td>';
                        foreach($solr[$element] as $index => $metadatavalue) {
                            // if it's a facet search
                            // make it a clickable search link
                            if(in_array($key, $filters)) {

                                $orig_filter = urlencode($metadatavalue);

                                echo '<a href="./search/*:*/' . $key . ':%22'.$orig_filter.'%22">'.$metadatavalue.'</a>';
                            }
                            else {
                                echo $metadatavalue;

                            }

                            if($index < sizeof($solr[$element]) - 1) {
                                echo '<br/>';
                            }
                        }
                        echo '</td></tr>';
                    }
                }
            }
            ?>

            <tr><th>Consult at</th>
                    <?php

                        echo '<td><a href="http://www.ed.ac.uk/information-services/library-museum-gallery/crc/visitor-information/opening-times-location" target="_blank"
                        title="University of Edinburgh, Centre for Research Collections">University of Edinburgh, Centre for Research Collections</a></td>';
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <button class="btn btn-info" onClick="history.go(-1);"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>Back to Search Results</button>
    </div>
</div>