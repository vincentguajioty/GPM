<?php
	require_once('config/config.php');
?>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <a href="http://www.guajioty.fr/majDist/gpm.zip"><b style="color:red;"><?php echo file_get_contents("https://www.guajioty.fr/majDist/gpmMAJ.php?versionClient=".$VERSION); ?></b></a>
        GPM - Version <?php echo $VERSION; ?>
    </div>
    Copyright &copy; 2017 Vincent Guajioty
</footer>