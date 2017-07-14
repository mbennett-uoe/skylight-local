<?php

// Set up some variables to easily refer to particular fields you've configured
$id_field = $this->skylight_utilities->getField('ID');
$title_field = $this->skylight_utilities->getField('Title');
$coverImageName = $this->skylight_utilities->getField("Image Name");
$location = $this->skylight_utilities->getField("Spatial Coverage");

$base_parameters = preg_replace("/[?&]sort_by=[_a-zA-Z+%20. ]+/", "", $base_parameters);
if ($base_parameters == "") {
    $sort = '?sort_by=';
} else {
    $sort = '&sort_by=';
}
?>

<div class="row">
    <div class="col-sm-9 col-xs-12">
        <div id="gallery-container">
            <div class="col-xs-12 hidden">
                <h5 class="text-muted">Showing <?php echo $rows ?> results </h5>
            </div>
            <script>
//                Will add locations to this array while iteration over the records
                var locations = [];
            </script>

            <?php
            foreach ($docs as $doc) {
//                Adding locations
                $title = isset( $doc[$title_field][0] ) ? $doc[$title_field][0] : "Untitled";

                if(isset( $doc[$location][0])) {
                    $coordinates = '' . $doc[$location][0] . '';
                    echo '<script> locations.push({"location" : "' . $coordinates . '", "title" : "' . str_replace(array("\n", "\r"), "", str_replace('"', '\"', $title)) . '", "index" : ' . $doc[$id_field] . '}); </script>';

                }
                //              Finding image
                if(isset( $doc[$coverImageName][0] )) {
                    $coverImageJSON = "http://test.cantaloupe.is.ed.ac.uk/iiif/2/" . $doc[$coverImageName][0];
                    $coverImageURL = $coverImageJSON . '/full/400,/0/default.jpg';
                    $thumbnailLink = '<a  class= "record-link" href="./record/' . $doc['id'] . '" title = "' . $title . '"> ';
                    $thumbnailLink .= '<img class="img-responsive" src ="' . $coverImageURL . '" title="' . $title . '" /></a>';
                }
                else{
                    $coverImageJSON = "http://test.cantaloupe.is.ed.ac.uk/iiif/2/missing.jpg";
                    $coverImageURL = $coverImageJSON . '/full/400,/0/default.jpg';
                    $thumbnailLink = '<a  class= "record-link" href="./record/' . $doc['id'] . '"> ';
                    $thumbnailLink .= '<img class="img-responsive" src ="' . $coverImageURL . '"/></a>';
                }
                ?>

                <div class="row record invisible <?php echo $doc[$id_field] ?>">
<!--                    Title   -->
                    <h4 class="visible-xs">
                        <a href="./record/<?php echo $doc['id'] ?>"><?php echo $title;?></a>
                    </h4>

<!--                    Thumbnail   -->
                    <?php echo $thumbnailLink; ?>

<!--                    Record info     -->
                    <div class="col-sm-9 hidden-xs result-info">
                        <h4 class="record-title">
                            <a href="./record/<?php echo $doc['id'] ?>"><?php echo $title; ?></a>
                        </h4>
                    </div>
                </div>
                <hr class="visible-xs">
                <?php
            } // end for each search result
            ?>
        </div>

<!--        Pagination  -->
        <div class="row">
            <div class="centered text-center">
                <nav>
                    <ul class="pagination">
                        <?php
                        foreach ($paginationlinks as $pagelink) {
                            echo $pagelink;
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

