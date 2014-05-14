<html>
    <head>
        <title><?php echo $title ?> - Previmed</title>
        <link href="<?php echo base_url()?>images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo link_tag('css/bootstrap.css')?>
        <?php echo link_tag('css/bootstrap-theme.css')?>
        <?php echo link_tag('js/bootstrap.min.js')?>
    </head>
    <body>
        <div class="container">       
            <div class='login' style="float: right;">
                    <?php echo $login?>
                </div>
            <div class="row"  >
                <div class="col-md-4">
                    <a href="<?php echo base_url()?>" ><img src="<?php echo base_url()?>images/logo.png"></img> </a>              
                </div>
                <div class="col-md-8">
                    <div class='sidebar'>
                    <?php echo $sidebar?>
                    </div>
                
                </div>
            </div>
            <div class="masthead">
                <?php echo $content?>
            </div>
            <div class="footer">
                2014 &copy; Ruano'Soft    
            </div>
        </div>
    </body>
</html>
