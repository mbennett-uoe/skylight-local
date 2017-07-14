 <div class="col-lg-3 col-md-3 hidden-sm hidden-xs" id="side_facet">
        <h3>Refine Results</h3>
    <?php
        $index = 0;

    if (isset($facets)) {?>
        <?php foreach ($facets as $facet) {

        $inactive_terms = array();
        $active_terms = array();

        ?>
        <div class="panel-group" id="accordion<?php echo $index ?>">
            <div class="panel panel-facets">
                <div class="panel-heading">
                    <span class="facet_title">
                    <a data-toggle="collapse" data-parent="#accordion" href="?query=h#collapse<?php echo $index ?>">

                        <?php echo $facet['name'] ?><i class="fa fa-chevron-down" aria-hidden="true"></i>

                    </a></span>
                </div>

                <div id="collapse<?php echo $index ?>" class="panel-collapse collapse in">
                    <div class="panel-body" id="<?php echo $index ?>_container">
                    <?php if(preg_match('/Date/',$base_search) && $facet['name'] == 'Date') {
                        $fpattern =  '#\/'.$facet['name'].'.*\]#';
                        $fremove = preg_replace($fpattern,'',$base_search, -1);

                        $fpattern =  '#\/'.$facet['name'].'.*\%5D#';
                        $fremove = preg_replace($fpattern,'',$fremove, -1);
                        ?>
                            Clear <?php echo $facet['name']; ?> filters <a class="deselect" href='<?php echo $fremove;?>'></a>
                        <br>
                    <?php }
                    $numterms = 0;
                    foreach($facet['terms'] as $term) {
                        if($term['active']) {
                            $active_terms[] = $term;
                        } else {
                            $inactive_terms[] = $term;
                        }
                        $numterms++;
                    }

                    if(sizeof($active_terms) > 0) { ?>
                        <?php foreach($active_terms as $term) {
                            $pattern =  '#\/'.rawurlencode($facet['name']).':%22'.preg_quote($term['name'],-1).'%22#';
                            $remove = preg_replace($pattern,'',$base_search, -1);
                            ?>
                            <?php echo $term['display_name'];?>
                                <a class="deselect" href='<?php echo $remove;?>'><i class="fa fa-close"></i>&nbsp; <span><?php echo $term['count']; ?></span></a><br><br>
                            <?php
                        }

                    }
                    foreach($inactive_terms as $term) { ?>
                        <a href='<?php echo $base_search; ?>/<?php echo $facet['name']; ?>:"<?php echo $term['name']; ?>"<?php echo $base_parameters ?>'><?php echo $term['display_name'];?>
                                <span><?php echo $term['count']; ?></span></a>
                        <br><br>
                        <?php
                    }

                    foreach($facet['queries'] as $term) {
                        $pattern =  '#\/'.rawurlencode($facet['name']).'.*\]#';
                        $remove = preg_replace($pattern,'',$base_search, -1);

                        $pattern =  '#\/'.rawurlencode($facet['name']).'.*\%5D#';
                        $remove = preg_replace($pattern,'',$remove, -1);

                        if($term['count'] > 0) {
                            ?>
                                <a class="deselect" href='<?php echo $remove; ?>/<?php echo $facet['name']; ?>:<?php echo $term['name']; ?><?php if(isset($operator)) echo '?operator='.$operator; ?>'><?php echo $term['display_name'];?>
                                    <span><?php echo $term['count']; ?></span></a><br><br>
                            <?php
                        }
                    }

                    if(empty($facet['terms']) && empty($facet['queries'])) { ?>
                        No matches<br><br>
                    <?php }
                    else {
                        if($numterms == $this->config->item('skylight_results_per_page')) { ?>
                            <a href="./browse/<?php echo $facet['name']; ?>">More ...</a><br><br>
                        <?php }
                    } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $index++;
        } ?>

    <?php } ?>
  </div><!-- end of side_facet -->
