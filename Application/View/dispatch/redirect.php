<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Apple devices fullscreen -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- Apple devices fullscreen -->
        <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <title><?php echo $this->lang('system_warning'); ?></title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo APP_URL; ?>public/admin/css/bootstrap.min.css">
        <!-- Bootstrap responsive -->
        <link rel="stylesheet" href="<?php echo APP_URL; ?>public/admin/css/bootstrap-responsive.min.css">
        <!-- Theme CSS -->
        <link rel="stylesheet" href="<?php echo APP_URL; ?>public/admin/css/style.css">
        <!-- Color CSS -->
        <link rel="stylesheet" href="<?php echo APP_URL; ?>public/admin/css/themes.css">

        <!-- jQuery -->
        <script src="<?php echo APP_URL; ?>public/admin/js/jquery.min.js"></script>

        <!-- Nice Scroll -->
        <script src="<?php echo APP_URL; ?>public/admin/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo APP_URL; ?>public/admin/js/bootstrap.min.js"></script>

        <!--[if lte IE 9]>
                <script src="<?php echo APP_URL; ?>public/admin/js/plugins/placeholder/jquery.placeholder.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('input, textarea').placeholder();
                    });
                </script>
            <![endif]-->

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo APP_URL; ?>public/admin/img/favicon.ico" />
        <!-- Apple devices Homescreen icon -->
        <link rel="apple-touch-icon-precomposed" href="<?php echo APP_URL; ?>public/admin/img/apple-touch-icon-precomposed.png" />
    </head>

    <body class='error'>
        <div class="wrapper">
            <div class="code"><span style="font-size: 36px;"><?php echo $this->lang('warning'); ?></span><i class="icon-warning-sign"></i></div>
            <div class="desc"><?php echo $message; ?></div>
            <div class="buttons">
                <?php if ($url == 'goback' || $url == ''): ?>
                    <div class="pull-left"> <a class="btn" href="javascript:history.back();"><i class="icon-arrow-left"></i><?php echo $this->lang('history_back'); ?></a> </div>
                    <?php if (isset($_SERVER['HTTP_REFERER']) == false): ?>
                        <div class="pull-right"> <a class="btn" href="javascript:window.close();"><i class="icon-remove"></i><?php echo $this->lang('close'); ?></a> </div>
                    <?php endif; ?>
                <?php elseif ($url == "close"): ?>
                    <div class="pull-left"> <a class="btn" href="javascript:window.close();"><i class="icon-remove"></i><?php echo $this->lang('close'); ?></a> </div>
                <?php elseif ($url): ?>
                    <div class="pull-right"> <a class="btn" href="<?php echo $url; ?>"><i class="icon-arrow-right"></i><?php echo $this->lang('click_here_redirect'); ?>(<i id="wait"><?php echo $second; ?></i>)</a> 
                        <script type="text/javascript">
                            (function() {
                                    var wait = document.getElementById('wait'),href = '<?php echo $url; ?>';
                                    var interval = setInterval(function() {
                                            var time = wait.innerHTML;
                                            if (time>0) {
                                                time--;
                                            }
                                            if (time <= 1) {
                                                    if (parent.window) {
                                                            parent.window.location.href = href;
                                                    } else {
                                                            location.href = href;
                                                    }
                                                    clearInterval(interval);
                                            };
                                    },
                                    1000);
                            })();
                        </script>
                    </div>
                <?php endif ?>
            </div>
    </body>
</html>
