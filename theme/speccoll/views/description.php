    <div class="col-description">
        <dl>
            <?php foreach($descriptiondisplay as $key) {

                $element = $this->skylight_utilities->getField($key);

                if(isset($solr[$element])) {

                    echo '<dt>' . $key . '</dt>';

                    echo '<dd>';
                    foreach($solr[$element] as $index => $metadatavalue) {
                        // if it's a facet search
                        // make it a clickable search link
                        if(in_array($key, $filters)) {

                            $orig_filter = urlencode($metadatavalue);
                            $lower_orig_filter = strtolower($metadatavalue);
                            $lower_orig_filter = urlencode($lower_orig_filter);

                            echo '<a href="./search/*:*/' . $key . ':%22'.$lower_orig_filter.'%7C%7C%7C'.$orig_filter.'%22">'.$metadatavalue.'</a>';
                        }
                        else {
                            echo $metadatavalue;
                        }
                        if($index < sizeof($solr[$element]) - 1) {

                            echo '; ';
                        }
                    }
                    echo '</dd>';
                }
            } ?>
        </dl>
    </div>
