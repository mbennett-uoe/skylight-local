
    <?php

        // Set up some variables to easily refer to particular fields you've configured
        // in $config['skylight_searchresult_display']

        $title_field = $this->skylight_utilities->getField('Title');
        $author_field = $this->skylight_utilities->getField('Creator');
        $date_field = $this->skylight_utilities->getField('Date');
        $type_field = $this->skylight_utilities->getField('Type');
        $abstract_field = $this->skylight_utilities->getField('Agents');
        $subject_field = $this->skylight_utilities->getField('Subject');

        $base_parameters = preg_replace("/[?&]sort_by=[_a-zA-Z+%20. ]+/","",$base_parameters);
        if($base_parameters == "") {
            $sort = '?sort_by=';
        }
        else {
            $sort = '&sort_by=';
        }
    ?>

    <div class="col-md-9 col-sm-9 col-xs-12">
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
        <div class="row search-row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 results-num">
                <h5 class="text-muted">Showing <?php echo $rows ?> results </h5>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 results-num sort">
                    <h5 class="text-muted">Sort By:
                    <?php foreach($sort_options as $label => $field) {
                        if($label == 'Relevancy')
                        {
                            ?>
                            <em><a href="<?php echo $base_search.$base_parameters.$sort.$field.'+desc'?>"><?php echo $label ?></a></em>
                            <?php
                        }
                        else {
                    ?>

                        <em><?php echo $label ?></em>
                        <?php if($label != "Date") { ?>
                        <a href="<?php echo $base_search.$base_parameters.$sort.$field.'+asc' ?>">A-Z</a> |
                        <a href="<?php echo $base_search.$base_parameters.$sort.$field.'+desc' ?>">Z-A</a>
                    <?php } else { ?>
                        <a href="<?php echo $base_search.$base_parameters.$sort.$field.'+desc' ?>">newest</a> |
                        <a href="<?php echo $base_search.$base_parameters.$sort.$field.'+asc' ?>">oldest</a>
                  <?php } } } ?>
                </h5>
            </div>

        </div>
        <?php
        foreach ($docs as $index => $doc) {
        ?>
            <div class="row search-row">

            <h3><a href="./record/<?php echo $doc['id']?>/<?php echo $doc['types'][0]?>"><?php echo $doc[$title_field]; ?></a></h3>
            <?php
            if (isset($doc["component_id"])) {
                $component_id = $doc["component_id"];
                echo'<div class="component_id">' . $component_id . '</div>';
            } ?>

                <?php if(array_key_exists($author_field,$doc)) { ?>
                    <?php

                    $num_authors = 0;
                    foreach ($doc[$author_field] as $author) {
                        $orig_filter = urlencode($author);

                        echo '<a class="agent" href="./search/*:*/Agent:%22'.$orig_filter.'%22">'.$author.'</a>';
                        $num_authors++;
                        if($num_authors < sizeof($doc[$author_field])) {
                            echo ' ';
                        }
                    }
                    ?>
                <?php } ?>

                <?php if(array_key_exists($subject_field,$doc)) { ?>
                    <div class="tags">
                    <?php

                    $num_subject = 0;
                    foreach ($doc[$subject_field] as $subject) {

                        $orig_filter = urlencode($subject);
                        echo '<a class="subject" href="./search/*:*/Subject:%22'.$orig_filter.'%22">'.$subject.'</a>';
                        $num_subject++;
                        if($num_subject < sizeof($doc[$subject_field])) {
                            echo ' ';
                        }
                    }

                    ?>
                    </div>
                <?php } ?>
            </div> <!-- close row-->
            <?php

        } // end for each search result

        ?>
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
    </div> <!-- close col 9 -->