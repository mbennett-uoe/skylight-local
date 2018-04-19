<div class="content">
    <?php

    $errors = false;
    $errorMessage = "";

    // check if we can get to solr
    if($error_message !== "") {
        $errors = true;
        $errorMessage = "Unable to connect to solr.";
    }
    else if(!count($docs) > 0) {
        $errors = true;
        $errorMessage = "There are no items in this collection.";
    }

    ?>

    <?php if (!$errors) { ?>

        <h1>Everything is OK</h1>

    <?php } else { ?>

        <h1>There are problems</h1>

        <p><?php echo $errorMessage; ?></p>

    <?php } ?>
</div>