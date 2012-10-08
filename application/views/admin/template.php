<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=I18n::$lang ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="<?=I18n::$lang ?>" /> 
		<title>Flow <?php echo $title; ?></title>
	    <?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), PHP_EOL ?>
        <link rel="icon" type="image/vnd.microsoft.icon" href="/public/image/admin/flow.png" />
        <link rel="shortcut icon" href="/public/image/admin/flow.png" />
        <script type="text/javascript">URL_BASE = "<?=URL::base();?>"; </script>
	</head>
	<body>
    <div id="nav">
        <?=$lightbox?>
    	<?=$menu;?>
    	<?=$content;?>
    </div>
	<?php foreach ($scripts as $file) echo HTML::script($file), PHP_EOL ?>
	<script>var msgs = <?=($mensagens)?($mensagens):('[]')?>;</script>   
	</body>
</html>