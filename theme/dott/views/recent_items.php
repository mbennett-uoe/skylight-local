<ul class="listing">

    <?php

    $title_field = $this->skylight_utilities->getField('Title');
    $author_field = $this->skylight_utilities->getField('Agent');
    $date_field = $this->skylight_utilities->getField('Date');
    $type_field = $this->skylight_utilities->getField('Type');
    $subject_field = $this->skylight_utilities->getField('Subject');

    foreach ($recentitems as $index => $doc) { ?>

        <li<?php if($index == 0) { echo ' class="first"'; } elseif($index == sizeof($recentitems) - 1) { echo ' class="last"'; } ?>>

            <h3><a href="./record/<?php echo $doc['id']?>/<?php echo $doc['types'][0]?>"><?php echo $doc[$title_field]; ?></a></h3>
            <div class = "iteminfo">

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


            </div> <!-- close item-info -->

            <div class="clearfix"></div>
        </li>
    <?php } ?>
</ul>