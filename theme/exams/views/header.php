<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <base href="<?php echo base_url() . index_page(); if (index_page() !== '') { echo '/'; } if ($this->config->item('skylight_url_prefix') != "") { echo $this->config->item('skylight_url_prefix'); echo '/'; } ?>">

    <title><?php echo $page_title; ?></title>

    <link rel="stylesheet" href="<?php echo base_url(); ?>theme/<?php echo $this->config->item('skylight_theme'); ?>/css/style.css?v=2">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/jquery-ui-1.10.4/themes/base/minified/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css">

    <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>theme/<?php echo $this->config->item('skylight_theme'); ?>/images/favicon.ico">

    <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
    <script src="<?php echo base_url()?>assets/modernizr/modernizr-1.7.min.js"></script>
    <script src="<?php echo base_url()?>assets/jquery-1.11.0/jquery-1.11.0.min.js"></script>
    <script src="<?php echo base_url()?>assets/jquery-ui-1.10.4/ui/minified/jquery-ui.min.js"></script>
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url()?>assets/jquery-1.11.0/jcarousel/jquery.jcarousel.min.js"></script>
    <script src="https://www.google-analytics.com/analytics.js"></script>

    <?php if (isset($solr)) { ?><link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
        <link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />

        <?php

        foreach($metafields as $label => $element) {
            $field = "";
            if(isset($recorddisplay[$label])) {
                $field = $recorddisplay[$label];
                if(isset($solr[$field])) {
                    $values = $solr[$field];
                    foreach($values as $value) {
                        ?>  <meta name="<?php echo $element; ?>" content="<?php echo $value; ?>"> <?php
                    }
                }
            }
        }

    } ?>

    <script>
        $(function() {
           $( "#q" ).autocomplete({
                source: "<?php echo base_url() . index_page(); if (index_page() !== '') { echo '/'; } ?>autocomplete",
                minLength: 2,
                select: function(event, ui) {
                   $("#q").val(ui.item.label);
                   $("#searchForm").submit();
               }

           });
        });
    </script>

    <!-- Google Analytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php echo $ga_code ?>', 'auto');
        ga('send', 'pageview');

    </script>
    <!-- End Google Analytics -->

</head>

<body>

<nav id="menu">
    <ul class="menu-links">
        <li><a href="./feedback/" title="Feedback Link" class="last" tabindex="4">Feedback</a></li>
        <li><a href="./help" title="Help Page" tabindex="3">Help</a></li>
        <li><a href="./about/" title="About Exam Papers Online" tabindex="2">About</a></li>
        <li><a href="./" title="Exam Papers Online Home" tabindex="1">Home</a></li>
    </ul>
</nav>
<div class="clearfix"></div>
<div id="container">

    <header>
        <div class="uofe-stuff">
            <a href="http://www.ed.ac.uk" title="University of Edinburgh Home" class="uofe-logo"></a>
            <a href="http://www.ed.ac.uk" title="University of Edinburgh Home" class="uofe-title"></a>
        </div>
        <div class="is-stuff">
            <a href="/" title="Exam Papers Online banner showing students studying for exams" class="exams-banner"></a>
        </div>

        <!-- Breadcrumbs -->
        <div id="breadTrail">
            <ul>
                <li class="breadHome"><a href="http://www.ed.ac.uk" title="University of Edinburgh Home">University Homepage</a></li>
                <li>
                    <a href="http://www.ed.ac.uk/schools-departments" title="Schools and Departments">Schools &amp; Departments</a>
                </li>
                <li>
                   <a href="http://www.ed.ac.uk/schools-departments/information-services" title="Information Services">Information Services</a>
                </li>
                <li>
                    <a href="http://www.ed.ac.uk/schools-departments/information-services/library-museum-gallery" title="Library Essentials">Library Essentials</a>
                </li>
                <li class="breadThis">
                    <a href="">Exam Papers Online Home</a>
                </li>
            </ul>
        </div>
        <!-- end of Breadcrumbs -->

        <form action="./redirect/" method="post" id="searchForm">
            <fieldset class="search">
                <input type="text" name="q" value="<?php if (isset($searchbox_query)) echo str_replace("+", " ", urldecode($searchbox_query)); ?>" id="q"  placeholder="Course Title / Course Code"  tabindex="5" />
                <input type="submit" name="submit_search" class="btn" value="Search" id="submit_search" title="Submit search">
                <a href="./search" class="advanced" title="Reset search text">Reset search</a>

            </fieldset>
        </form>

    </header>

    <div id="main" role="main" class="clearfix">