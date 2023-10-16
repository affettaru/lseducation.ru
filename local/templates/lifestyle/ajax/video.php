<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
	<?if($_POST["tip"] == "amazon"):?>
	    <div class="video as-load-video">
	        <video id="video-player" style="width:100%; margin:0 auto; display:block;" controls></video>
	    </div>
    <? else: ?>
	    <div class="video as-load-video">
	        <iframe style="width:100%; height: 668px; margin:0 auto; display:block;" src="https://www.youtube.com/embed/<?=$_POST["video"]?>?autoplay=1&rel=0&showinfo=0" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
	    </div>
	<?endif;?>

<script src="https://player.live-video.net/1.7.0/amazon-ivs-player.min.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/public/assets/js/affetta.js"></script>
<script type="text/javascript">var P_URL = "<?=$_POST["video"]?>";</script>
