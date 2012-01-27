<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo ViewHelper::config('app.title') ?></title>
        <meta name="description" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="<?php echo ViewHelper::url("assets/css/bootstrap.css") ?>" rel="stylesheet">
        <link href="<?php echo ViewHelper::url("assets/css/jquery-ui.css") ?>" rel="stylesheet">
        <link href="<?php echo ViewHelper::url("assets/css/app.css") ?>" rel="stylesheet">
        <link href="<?php echo ViewHelper::url("assets/css/style.css") ?>" rel="stylesheet">
        <?php
        foreach ($css as $cssfile) {
            echo '<link href="' . BASE_URL . 'assets/' . $cssfile . '" rel="stylesheet">';
        }
        ?>

        <script type="text/javascript" >
            window.BASE_URL = "<?php echo BASE_URL; ?>";
        </script>

        <script type="text/javascript" src="<?php echo BASE_URL . 'assets/js/jquery.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo BASE_URL . 'assets/js/jquery-ui.js'; ?>"></script>
        <?php
        foreach ($js as $jsfile) {
            echo '<script type="text/javascript" src="' . BASE_URL . 'assets/' . $jsfile . '"></script>';
        }
        ?>
        <script type="text/javascript" src="<?php echo BASE_URL . 'assets/js/script.js'; ?>"></script>

    </head>

    <body>

        <div class="topbar">

            <div class="fill">

                <div class="container">

                    <a class="brand" href="<?php ViewHelper::url() ?>"><?php echo ViewHelper::config('app.title') ?></a>

                    <ul class="nav">
                        <li><a href="<?php ViewHelper::url() ?>">Home</a></li>
                        <li><a href="<?php ViewHelper::url('?page=events') ?>">Events</a></li>
                        <li><a href="<?php ViewHelper::url('?page=about') ?>">About</a></li>
                    </ul>

                    <span class="pull-right">
                        <?php if ($_SESSION['user']): ?>
                            <span>

                                <?php
                                echo '<a title="Update your name" href="' . ViewHelper::url('?page=username', true) . '">';

                                if (isset($_SESSION['user']['name']))
                                    echo $_SESSION['user']['name'];
                                else
                                    echo $_SESSION['user']['email'];

                                echo '</a>';
                                ?>


                            </span> | <a href="<?php ViewHelper::url('?page=logout') ?>">Logout</a>
<?php else: ?>
                            <a href="<?php ViewHelper::url('?page=login') ?>">
                                <img width="150px" height="26px" src="<?php ViewHelper::url('assets/images/google_signin.png') ?>" alt="Sign in with Google">
                            </a>
<?php endif; ?>
                    </span>

                </div>

            </div>

        </div>

        <div class="container">


            <div class="content">

                <div class="row">

                    <div id="main-content" class="span10">
<?php ViewHelper::flushMessage(); ?>