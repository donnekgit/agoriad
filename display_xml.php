<?php

include("./klebran/config.php");
include("./includes/fns.php");

if (isset($lg)) { $lg=$lg; } else { $lg="cy"; }
checklg($lg);

include("./includes/trans.php");

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $tr_disp_title; ?></title>
<link rel="stylesheet" type="text/css" href="klebran.css">
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="myajax.js"></script>
<script type="text/javascript" src="show_hide.js"></script>
</head>
<body>

<div id="header"></div>

<div id="navbar">
<?php include("./includes/navbar.php"); ?>
</div>

<?php

$cur_ip = $_SERVER['REMOTE_ADDR'];
$cur_ip=preg_replace("/\./", "_", $cur_ip);

echo "<div id=\"to_be_corrected\">";
$sql_show="select * from gram_xml_$cur_ip order by id";
$result_show=pg_query($db_handle, $sql_show);
while ($row_show=pg_fetch_object($result_show))
{
	// only do the lookup and formatting for non-punctuation
	if ($row_show->pos != 'K')
	{
		$word_id=$row_show->id;
		$word=$row_show->word;
		$pos=$row_show->pos;
		$number=$row_show->number;
		$gender=$row_show->gender;
		$mutation=$row_show->mutation;
		$tense=$row_show->tense;
		$error=$row_show->error;

		if ($row_show->meaning=="")
		{
			$orig=$word;
		
			if ($mutation=="1") { $orig=de_meddal($word); }
			if ($mutation=="2") { $orig=de_trwynol($word); }
			if ($mutation=="3") { $orig=de_llaes($word); }

			// remove elisions to do the lookup properly
			$orig=preg_replace("/\'[rniwu]/", "", $orig);
		
			// lower-case the word
			$orig=strtolower($orig);
		
			// don't look up numbers and figures
			if (!preg_match("/[0-9]+/", "$orig"))
			{
				// or inflected verbs (unless you fill in the else-clause)
				if ($tense=="")
				{
					// the soft mutation can be caused by m or b - we need to allow for this in the lookup
					if (preg_match("/\[mb\]/", $orig))
					{
						$opt1=preg_replace("/\[mb\]/", "m", $orig);
						$opt2=preg_replace("/\[mb\]/", "b", $orig);
						$sql_lu="select * from eurfa_nmni where welsh='$opt1' or welsh='$opt2'";
					}
					else
					{
						$sql_lu="select * from eurfa_nmni where welsh='$orig'";
					}
					$result_lu=pg_query($db_handle, $sql_lu);
					while ($row_lu=pg_fetch_object($result_lu))
					{
						// replace the lookup word with the one actually found, to cover the [mb] situation
						$orig = $row_lu->welsh;
						$meaning .= $row_lu->english."; ";
					}
				} // end no_inflected_verbs if-clause
				else
				{
					// perhaps look up inflected verbs in the eurfa_gbl table - this would increase the time required
				}
			}
		
			// strip hanging semicolons off the end
			$meaning=substr($meaning, 0, -2);
	
			$sql_in="update gram_xml_$cur_ip set base='$orig', meaning='$meaning' where id=$word_id";
			$result_in=pg_query($db_handle, $sql_in);
		}
		else
		{
			$orig=$row_show->base;
			$meaning=$row_show->meaning;
		}

		// set up the layout of the pop-up
		if ($lg=="en")
		{
			$pos=show_pos_en($row_show->pos);
			$parse_info=$pos;
			if ($number != "") {$number=show_number_en($row_show->number); $parse_info .= ", ".$number;}
			if ($gender != "") {$gender=show_gender_en($row_show->gender); $parse_info .= ", ".$gender;}
			if ($mutation != "") {$mutation=show_mutation_en($row_show->mutation); $parse_info .= ", ".$mutation;}
			if ($tense != "") {$tense=$row_show->tense; $parse_info .= ", ".$tense;}
		}
		else
		{
			$pos=show_pos_cy($row_show->pos);
			$parse_info=$pos;
			if ($number != "") {$number=show_number_cy($row_show->number); $parse_info .= ", ".$number;}
			if ($gender != "") {$gender=show_gender_cy($row_show->gender); $parse_info .= ", ".$gender;}
			if ($mutation != "") {$mutation=show_mutation_cy($row_show->mutation); $parse_info .= ", ".$mutation;}
			if ($tense != "") {$tense=$row_show->tense; $parse_info .= ", ".$tense;}
		}
	
		// with inflected verbs, $meaning won't contain anything 
		// unless you have done a lookup above in the gbl table
		if (is_string($meaning))
		{
			$full_breakfast=$orig." (".$meaning.") : ".$parse_info;
		}
		else
		{
			$full_breakfast=$orig." : ".$parse_info;
		}
	
		// don't format numbers and figures
		if (!preg_match("/[0-9]+/", "$orig"))
		{
			// sort out words which are flagged as an error
			if ($error != "")
			{
				if (preg_match("/^AN/", $error))
				{
					if ($lg=="en") { $error="This word is not in the current version of Eurfa."; }
						else { $error="Ni chafwyd y gair yma yn y fersiwn cyfredol o Eurfa."; }
					$word="<a class=\"anhysbys\" title=\"$error\" href=\"\" onMouseOver=\"window.status='$error'; return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"getData('leven.php?lg=$lg&input=$word&word_id=$word_id', 'targetDiv'); return false;\">$word</a>";
				}
				elseif (preg_match("/^GR/", $error))
				{
					$gram=preg_match("/GRAM{\^?(.+)}/", $error, $foreign);
					if ($lg=="en") { $error="This word may be foreign - the sequence /$foreign[1]/ is not common."; }
						else { $error="Efallai bod y gair yma yn ddieithr - mae dilyniant  fel /$foreign[1]/ yn anghyffredin."; }
					$word="<a class=\"dieithr\" title=\"$error\" href=\"\" onMouseOver=\"window.status='$error'; return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"getData('dieithr.php?lg=$lg&word=$word&word_id=$word_id', 'targetDiv'); return false;\" >$word</a>";
				}
				elseif (preg_match("/^SE/", $error))
				{
					if ($lg=="en") { $error="Soft mutation is missing on this word."; }
						else { $error="Mae treiglad meddal ar goll ar y gair yma."; }
					$word="<a class=\"meddal\" title=\"$error\" href=\"treiglad.php\" onMouseOver=\"window.status='$error'; return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"getData('treiglad.php?lg=$lg&word=$word&word_id=$word_id&mutation=meddal', 'targetDiv'); return false;\">$word</a>";
				}
				elseif (preg_match("/^LL/", $error))
				{
					if ($lg=="en") { $error="Aspirate mutation is missing on this word."; }
						else { $error="Mae treiglad llaes ar goll ar y gair yma."; }
					$word="<a class=\"llaes\" title=\"$error\" href=\"treiglad.php\" onMouseOver=\"window.status='$error'; return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"getData('treiglad.php?lg=$lg&word=$word&word_id=$word_id&mutation=llaes', 'targetDiv'); return false;\">$word</a>";
				}
				elseif (preg_match("/^UR/", $error))
				{
					if ($lg=="en") { $error="Nasal mutation is missing on this word."; }
						else { $error="Mae treiglad trwynol ar goll ar y gair yma."; }
					$word="<a class=\"trwynol\" title=\"$error\" href=\"treiglad.php\" onMouseOver=\"window.status='$error'; return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"getData('treiglad.php?lg=$lg&word=$word&word_id=$word_id&mutation=trwynol', 'targetDiv'); return false;\">$word</a>";
				}
				elseif (preg_match("/^CO/", $error))
				{
					$gram=preg_match("/COMHFHOCAL{(.+)\+(.+)}/", $error, $compound);
					if ($lg=="en") { $error="Perhaps a compound of /$compound[1]/ and /$compound[2]/."; }
						else { $error="Efallai gair wedi ei gyfansoddi o /$compound[1]/ a(c) /$compound[2]/"; }
					$word="<a class=\"cyfair\" title=\"$error\" href=\"\" onMouseOver=\"window.status='$error'; return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"getData('cyfair.php?lg=$lg&compound1=$compound[1]&compound2=$compound[2]&word_id=$word_id', 'targetDiv'); return false;\">$word</a>";
				}
			}
			// do a pop-up for words which have no errors, giving POS data and meaning
			else
			{
				$word="<a class=\"eurfa\" title=\"$full_breakfast\" onMouseOver=\"window.status='$full_breakfast'; return true;\" onMouseOut=\"window.status=''; return true;\">$word</a>";
			}
		}
	
		unset($meaning);
		unset($orig);
	
	} // end non-K if-clause
	else
	{
		$word=$row_show->word;
	} // end K else-clause

	// to check punKtuation, comment out the $word line above and the $stream line below
	// and uncomment this one
	//echo $word." ";
	$stream .= $word." ";

}

echo fix_K($stream);

// rough count of how many words are in the selection
$sql_w="select count(*) as wordcount from gram_xml_$cur_ip where word ~ '[a-zA-Z0-9]+'";
$result_w=pg_query($db_handle, $sql_w);
$row_w=pg_fetch_object($result_w);
echo "<br /><br /><em>(".$row_w->wordcount." ".$tr_disp_wordno.")</em>";

echo "</div>";

?>

<div id="targetDiv">
<h3><?php echo $tr_disp_corrs; ?></h3>
<div id="sidenote">
<?php include("./includes/display_info.php"); ?>
</div>

<h4><?php echo $tr_disp_high; ?></h4>
<p id="sidenote">
<a class="anhysbys" href=""><?php echo $tr_disp_anhysbys; ?></a><br />
<a class="dieithr" href=""><?php echo $tr_disp_dieithr; ?></a><br />
<a class="meddal" href=""><?php echo $tr_disp_meddal; ?></a><br />
<a class="trwynol" href=""><?php echo $tr_disp_trwynol; ?></a><br />
<a class="llaes" href=""><?php echo $tr_disp_llaes; ?></a><br />
<a class="cyfair" href=""><?php echo $tr_disp_cyfair; ?></a><br />
<a class="anarferol" href=""><?php echo $tr_disp_anarferol; ?></a>
</p>

</div>

<?php include("./includes/footer.php"); ?>

</body>
</html>
