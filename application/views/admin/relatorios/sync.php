<div class="scrollable_content clear" style="height:500px;">
<span class='list_alert round'><?=count($r);?> objetos encontrados.</span>
<?
foreach ($r as $key => $msg) {
	echo "<div>".$msg."</div>";
}
?>
</div>