<div class="col-md-3 col-sm-3 hidden-xs" >

    <div class="sidebar-nav related-items">
        <ul class="list-group">
            <li class="list-group-item active">Related Items</li>

        <?php

        // if there are related items
        if(count($related_items) > 0) {

            $type_field = $this->skylight_utilities->getField('Type');
            $id_field = $this->skylight_utilities->getField('Identifier');

            foreach ($related_items as $index => $doc) {
            ?>
                <li class="list-group-item">
                    <a class="related-record" href="./record/<?php echo $doc['id']?>/<?php echo $doc['types'][0]?>"><?php echo $doc[$title_field][0]; ?></a>
                    <?php
                    if (isset($doc["component_id"])) {
                        $component_id = $doc["component_id"];
                        echo'<div class="component_id">' . $component_id . '</div>';
                    } ?>
                    <?php echo $doc["dates"]; ?>
                </li>
            <?php }

        }
        // else there aren't any related items
        else { ?>

            <li class="list-group-item">None.</li>

        <?php }?>
    </ul>
</div>
</div>