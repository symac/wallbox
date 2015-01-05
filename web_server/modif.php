<?php
	require_once("header.php");
	ini_set("display_errors", True);
	$code = $_GET["code"];
	$dir = "/home/pi/mp3_wallbox/";
	$dir_mp3 = "/home/pi/mp3/";

	$file = $dir.$code.".mp3";

	if (isset($_POST["code"]))
	{
		// Mise à jour de la page OK, on va recréer le lien
		$code = $_POST["code"];
		$mp3 = $_POST["mp3"];

		$target = $dir_mp3.$mp3;
		$link_name = $dir."$code.mp3";
		
		// On supprime le lien s'il existe
		@unlink($link_name);

		if ($mp3 != "")
		{
			print "$target => $link_name<br/>";
			if (symlink($target, $link_name))
			{
				print "Enregistrement effectué";
			}
			else
			{
				print "Probleme creation lien";
			}

		}
		else
		{
			print "suppression du lien effectué";
		}
		print "<br/><a href='index.php'>Revenir à la liste</a>";
		exit;
	}


	if ( (file_exists($file)) and (filetype($file) == "link") )
	{
		print "Lien actuel pointe vers : ".basename(readlink($file))."<br/>";
	}

	$files_mp3 = scandir($dir_mp3);

	print "<form method='POST'>";
	print "<input type='hidden' name='code' value='$code'/>";
	print "<select name='mp3'>\n";
	print "<option value='' select/>";
	foreach ($files_mp3 as $code => $mp3)
	{
		if (preg_match("/\.mp3$/", $mp3))
		{
			print "<option value=\"$mp3\">$mp3</option>\n";
		}
	}
	print "</select>";
	print "<input type='submit' value='Enregistrer'/>";
	print "</form>";
?>