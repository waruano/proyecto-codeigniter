<html>
    <head>
        <title><?php echo $title ?> - Previmed</title>
        <link href="<?php echo base_url()?>images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo link_tag('css/bootstrap.css')?>
        <?php echo link_tag('css/bootstrap-theme.css')?>        
        <script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.11.1.min.js" > </script>
        <script type="text/javascript" src="<?php echo base_url()?>js/bootstrap.min.js" > </script>
    </head>
    <body>
    
        <div class="container" style="padding-top: 10px;">             
            <div class='login' style="float: right;">
                    <?php echo $login?>
                </div>
            <div class="row"  >
                <div class="col-md-3">
                    <a href="<?php echo base_url()?>" ><img src="<?php echo base_url()?>images/logo.png"></img> </a>              
                </div>
                <div class="col-md-9">
                    <div class='sidebar'>
                    <?php echo $sidebar?>
                    </div>
                
                </div>
            </div>
            <div class="masthead">
                <?php echo $content?>
            </div>
            <div class="footer" style="font-size: 10px;">
                <br/><br/>
                Copyright &copy; 2014 <a href="<?php echo base_url() . "index.php/home/acercade"; ?>" >SmartApps</img> </a>              
            </div>
        </div>
    </body>
</html>
