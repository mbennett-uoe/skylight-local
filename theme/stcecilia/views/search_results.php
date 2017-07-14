<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ">
    <?php

    // Set up some variables to easily refer to particular fields you've configured
    // in $config['skylight_searchresult_display']

    $title_field = $this->skylight_utilities->getField('Title');
    $maker_field = $this->skylight_utilities->getField('Maker');
    $date_field = $this->skylight_utilities->getField('Date Made');
    $bitstream_field = $this->skylight_utilities->getField('Bitstream');
    $thumbnail_field = $this->skylight_utilities->getField('Thumbnail');
    $base_parameters = preg_replace("/[?&]sort_by=[_a-zA-Z+%20. ]+/","",$base_parameters);
    if($base_parameters == "") {
        $sort = '?sort_by=';
    }
    else {
        $sort = '&sort_by=';
    }
    ?>

    <div class="container-fluid">
        <div class="searchFoundRow">
            <span class="searchFound"><?php echo $rows ?> instruments found </span>
        </div>
        <div id="results-grid" class="grid">
            <div class="grid-sizer col-xs-3"></div>
            <?php
            $link_uri_field = $this->skylight_utilities->getField("ImageURI");
            //$link_uri_field = '';
            //$link_uri_field = 'http://images.is.ed.ac.uk/luna/servlet/iiif/UoEart~2~2~73511~166929/full/full/0/default.jpg';

            $content = true;

            try{
                $content = @file_get_contents('http://images.is.ed.ac.uk/luna/images/favicon.ico',0,null,0,1);
            } catch (Exception $e) {
                // Handle exception
            }

            foreach ($docs as $index => $doc) {

                $bitstream_array = array();
                $thumbnailLink = "";
                $thumbnailImg = "";

                //TODO dcidentifieruri is a temporary location for the IIIF URIs
                if (isset($doc[$link_uri_field][0]))
                {
                    $linkURI = $doc[$link_uri_field][0];
                    //$linkURI = str_replace('/full/full/0/default.jpg','', $linkURI);

                    if (strpos($linkURI, 'luna') > 0 )
                    {
                        if (false === $content) {
                            $thumbnailLink  =  'href="./record/'.$doc['id'].'" title = "'. $doc[$title_field][0].'"';
                            $thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="../theme/stcecilia/images/comingsoon.gif"  title="' . $doc[$title_field][0] . '" />';
                        }
                        else
                        {
                            /*
                            list($width, $height, $type, $imgText) = getimagesize($linkURI);
                            $portrait = true;
                            if ($width > $height) {
                                $portrait = false;
                            }
                            if ($portrait) {
                                $iiifurlsmall = str_replace('full/full/0/default.jpg', 'full/,266/0/default.jpg', $linkURI);
                                //$iiifurlsmall = $linkURI.'full/,266/0/default.jpg';
                            } else {
                                $iiifurlsmall = str_replace('full/full/0/default.jpg', 'full/266,/0/default.jpg', $linkURI);
                                //$iiifurlsmall = $linkURI.'full/266,/0/default.jpg';
                            }


                            list($width, $height, $type, $imgText) = getimagesize($linkURI);
                            */

                            $linkURI = str_replace('full/full', 'full/!300,300', $linkURI);
                            $thumbnailLink = 'href="./record/' . $doc['id'] . '" title = "' . $doc[$title_field][0] . '"';
                            $thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="' . $linkURI . '"  title="' . $doc[$title_field][0] . '" />';
                            //$thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="' . $linkURI . '"  title="' . $doc[$title_field][0] . '" '.  $imgText .'/>';
                            //$thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="' . $linkURI.'/full/full/0/default.jpg"  title="' . $doc[$title_field][0] . '" ' . $imgText . '/>';
                            //$thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="' . $iiifurlsmall.'"  title="' . $doc[$title_field][0] . '" ' . $imgText . '/>';
                        }
                    }
                    else
                    {
                        $thumbnailLink  =  'href="./record/'.$doc['id'].'" title = "'. $doc[$title_field][0].'"';
                        $thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="../theme/stcecilia/images/comingsoon1.gif"  title="' . $doc[$title_field][0] . '" />';
                    }
                }
                else
                {
                    $thumbnailLink  =  'href="./record/'.$doc['id'].'" title = "'. $doc[$title_field][0].'"';
                    $thumbnailImg = '<img class="img-responsive record-thumbnail-search" src="../theme/stcecilia/images/comingsoon.gif"  title="' . $doc[$title_field][0] . '" />';
                }

                ?>

                <div class="grid-item col-xs-6 col-sm-6 col-md-3 col-lg-3">
                    <a <?php echo $thumbnailLink; ?> >
                        <div class="grid-item-container">
                            <div class="grid-item-content box">

                                <?php echo $thumbnailImg; ?>

                                <figcaption><span class="searchTitle"><?php echo $doc[$title_field][0]; ?></span><br>
                                    <span class="searchDate"><?php echo empty($doc[$maker_field][0]) ? "unknown" : $doc[$maker_field][0] ; ?></span><br>
                                    <span class="searchDate"><?php echo empty($doc[$date_field][0]) ? "unknown" : $doc[$date_field][0] ; ?></span></figcaption>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }?>
        </div>
        <div class="row">
            <div class="centered text-center">
                <nav>
                    <ul class="pagination pagination-sm pagination-xs">
                        <?php
                        foreach ($paginationlinks as $pagelink)
                        {
                            echo $pagelink;
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script>
    //init Masonry
    var $grid = $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        percentPosition: true
    });
    // layout Masonry after each image loads
    $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');
    });
</script>




