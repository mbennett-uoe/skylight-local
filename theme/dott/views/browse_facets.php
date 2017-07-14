
    <div class="pagination browse_pagination">
        <span class="no-results">
        <strong><?php echo $startrow ?>-<?php echo $endrow ?></strong> of
        <strong><?php echo $total_results ?></strong> results </span>
        <?php echo $pagelinks ?>
    </div>
    <br />
    <div class="browse_results">
        <div class="term_search">
            <form method="get" action="./browse/<?php echo $field; ?>">
                <div class="input-group">
                    <input name="prefix" class="form-control" id="prefix" value="" placeholder="Starts with: (case sensitive)" />
                    <span class="input-group-btn"><button type="submit" class="btn btn-primary">
                            <span class=" glyphicon glyphicon-search"></span>&nbsp; Search</button></span>
                </div>
            </form>
        </div>
        <br />
        <div class="browse_facets">
            <ul class="list-group">
                <?php foreach($facet['terms'] as $term) { ?>
                    <li class="list-group-item">
                        <span class="badge"><?php echo $term['count']; ?></span><a href='<?php echo $base_search; ?>/<?php echo $facet['name']; ?>
                            <?php echo $delimiter?>"<?php echo $term['name']; ?>"<?php if(isset($operator)) echo '?operator='.$operator; ?>'>
                            <?php echo $term['display_name'];?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>