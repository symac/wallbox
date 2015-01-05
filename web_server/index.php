<?php
	require_once("header.php");
	$lettres = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V"];
	$nb_max_cnt = 15;

	ini_set("display_errors", 1);
	$dir = "/home/pi/mp3_wallbox/";
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (filetype($dir.$file) == "link")
			{
				$link_files[preg_replace("/\.mp3/", "", $file)] = basename(readlink($dir . $file));
			}
		}
		closedir($dh);
	}

	// $link_files = scandir($dir);
	$dir_mp3 = "/home/pi/mp3/";
	$files_mp3 = scandir($dir_mp3);

	$tabForMp3 = array();

	$liens_actifs = Array();
	foreach ($link_files as $link)
	{
		$link = preg_replace("/\.mp3/", "", $link);
		$liens_actifs[$link] = 1;
	}

	print "<table>";
	$counter = 1;
	foreach ($lettres as $lettre)
	{
		for ($i = 0; $i <= 9 ; $i++)
		{
			$code = $lettre.$i;
			print "<tr>";
			print "<td>".$code."</td>";
			if (isset($link_files[$code]))
			{
				print "<td>&nbsp;".$link_files[$code]."</td>";
			}
			else
			{
				print "<td>&nbsp;</td>";
			}
			print "<td><a href='modif.php?code=$code'>edit</a></td>";
			print "<td style='width:700px'>";
			if (isset($link_files[$code]))
			{
				addPlayer($code, $counter);
				$tabForMp3[$counter] = $code;
				$counter++;				
			}
			print "</td>";
			print "</tr>";
		}
	}
	print "</table>";	

	function addJs($code, $counter)
	{
?>
			$("#jquery_jplayer_<?php echo $counter; ?>").jPlayer({
				ready: function () {
				$(this).jPlayer("setMedia", {
					title: "<?php echo $code; ?>.mp3",
					mp3: "/mp3/<?php echo $code; ?>.mp3?rnd=<?php print substr( md5(rand()), 0, 7);?>"
				});
				},
				swfPath: "/js",
				supplied: "mp3",
				wmode: "window",
				smoothPlayBar: true,
				keyEnabled: true,
				remainingDuration: true,
				toggleDuration: true,
				volume: 1,
				preload: "none",
				cssSelectorAncestor: "#jp_container_<?php echo $counter; ?>"
			});
<?php
	}

	function addPlayer($code, $counter)
	{
?>

	<div id="jquery_jplayer_<?php echo $counter; ?>" class="jp-jplayer"></div>
	<div id="jp_container_<?php echo $counter; ?>" class="jp-audio">
		<div class="jp-controls">
			<a class="jp-play"><i class="fa fa-play"></i></a>
			<a class="jp-pause"><i class="fa fa-pause"></i></a>
		</div>
		<div class="jp-progress">
			<div class="jp-seek-bar">
				<div class="jp-play-bar">
				</div>
			</div>
			<div class="jp-current-time"></div>
		</div>
		<div class="jp-no-solution">
			Media Player Error<br>
			Update your browser or Flash plugin
		</div>
	</div>
<?php		
	}
?>
	<script type="text/javascript">
		$(document).ready(function(){

<?php
	foreach ($tabForMp3 as $counter => $code)
	{
		addJs($code, $counter);
	}
?>
		});
	</script>


</body>