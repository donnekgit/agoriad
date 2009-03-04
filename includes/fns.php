<?php

//Copyright 2006 Kevin Donnelly, kevin@dotmon.com

/*
This file is part of Eurfa, a free dictionary for Welsh.

Eurfa is free software; you can redistribute it and/or modify it under the 
terms of the GNU General Public License as published by the Free Software 
Foundation; either version 2 of the License, or (at your option) any later version.

Eurfa is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

A copy of the GNU General Public License is included along with Eurfa in the file 
COPYING; if this file is missing, a copy is available at http://www.gnu.org/licenses/gpl.txt, 
or you can write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, 
Boston, MA  02110-1301  USA.
*/

function query($sql)
// simplify the query writing
// use this as: $result=query("select * from table")
{
    global $db_handle;
    return pg_exec($db_handle,$sql);
}

function checklg($var)
// tries to make the language calls a bit more secure
// by checking that they contain only "en" or "cy"
{
        if ($var =="en" or $var=="cy")
        {
        return true;
        }
        else
        {
        echo "Rhaid i chi benodi iaith<br>You need to specify a language";
        exit;
        }
}

function checktext($var)
{
	for ($i = 0; $i < strlen($var); $i++) 
	{
		if (!ereg("[-A-Za-z_0-9ËëÏïÖöÂâÊêÎîÔôÛûŴŵŶŷÁá ]", $var[$i]))
		{
               echo "Ni allaf dderbyn hynny...<br>I'm not allowed to accept that ...";
			exit;
		}
    }
}

function show_array($array)
// shows the contents of an array
{
	foreach ($array as $value)
	{
		if (is_array($value))
		{
			show_array($value); 
		}
		else
		{
			// note that htmlentities has been added here so that items in angle brackets will be shown on the webpage
			echo htmlentities($value) . "<br><br>"; 
		} 
	}
}

function colourme($text)
{
  $text=preg_replace("/((vbser|vblex|vpart))/", "<span style=\"color: #388c00; background-color: #bfff99;\">$1</span>", $text);
  $text=preg_replace("/((det))/", "<span style=\"color: #ff00ff; background-color: #fcc9fc;\">$1</span>", $text);
  $text=preg_replace("/((adj))/", "<span style=\"color: #8c5400; background-color: #ffd999;\">$1</span>", $text);
  $text=preg_replace("/(pronoun)/", "<span style=\"color: #54008c; background-color: #cc99ff;\">prn</span>", $text);
  $text=preg_replace("/(noun)/", "<span style=\"color: #cc99ff; background-color: #54008c;\">$1</span>", $text);
  $text=preg_replace("/(preposition)/", "<span style=\"color: #595959; background-color: #cccccc;\">pr</span>", $text);
  $text=preg_replace("/cnjcoo/", "<span style=\"color: #8c0e1c; background-color: #ff99a6;\">cnjcoo</span>", $text);
  //unknown words get a border
  $text=preg_replace("/\\*(.+)/", "<span style=\"border: #000099 solid 1px;\">$1</span>", $text);
  //$text=preg_replace("/((adj))/", "<span style=\"color: #8c5400; bacfile:///home/kevin/public_html/interface/includes/fns.phpkground-color: #ffd999;\">$1</span>", $text);
  //$text=preg_replace("/((adj))/", "<span style=\"color: #8c5400; background-color: #ffd999;\">$1</span>", $text);


  return $text;
}



function meddal($text)
// do soft mutations - note that the order of these regex replacements is significant;
// letters in the regex must be placed before their occurrence as replacements
{
	$text=preg_replace("/^b/", "f", $text);
	$text=preg_replace("/^B/", "F", $text);
	$text=preg_replace("/^d([^d])/", "dd$1", $text);
	$text=preg_replace("/^D([^d])/", "Dd$1", $text);
	$text=preg_replace("/^g/", "", $text);
	$text=preg_replace("/^G/", "", $text);
	$text=preg_replace("/^c([^h])/", "g$1", $text);
	$text=preg_replace("/^C([^h])/", "G$1", $text);
	$text=preg_replace("/^p([^h])/", "b$1", $text);
	$text=preg_replace("/^P([^h])/", "B$1", $text);
	$text=preg_replace("/^t([^h])/", "d$1", $text);
	$text=preg_replace("/^T([^h])/", "D$1", $text);
	$text=preg_replace("/^ll/", "l", $text);
	$text=preg_replace("/^Ll/", "L", $text);
	$text=preg_replace("/^rh/", "r", $text);
	$text=preg_replace("/^Rh/", "R", $text);
	$text=preg_replace("/^m/", "f", $text);
	$text=preg_replace("/^M/", "F", $text);
	return $text;
}

function trwynol($text)
// do nasal mutations
{
	$text=preg_replace("/^c([^h])/", "ngh$1", $text);
	$text=preg_replace("/^C([^h])/", "Ngh$1", $text);
	$text=preg_replace("/^p([^h])/", "mh$1", $text);
	$text=preg_replace("/^P([^h])/", "Mh$1", $text);
	$text=preg_replace("/^t([^h])/", "nh$1", $text);
	$text=preg_replace("/^T([^h])/", "Nh$1", $text);
	$text=preg_replace("/^g/", "ng", $text);
	$text=preg_replace("/^G/", "Ng", $text);
	$text=preg_replace("/^b/", "m", $text);
	$text=preg_replace("/^B/", "M", $text);
	$text=preg_replace("/^d([^d])/", "n$1", $text);
	$text=preg_replace("/^D([^d])/", "N$1", $text);
	return $text;
}

function llaes($text)
// do aspirate  mutations
{
	$text=preg_replace("/^c([^h])/", "ch$1", $text);
	$text=preg_replace("/^C([^h])/", "Ch$1", $text);
	$text=preg_replace("/^p([^h])/", "ph$1", $text);
	$text=preg_replace("/^P([^h])/", "Ph$1", $text);
	$text=preg_replace("/^t([^h])/", "th$1", $text);
	$text=preg_replace("/^T([^h])/", "Th$1", $text);
	return $text;
}

function add_h($text)
// add h- before initial vowels
{
	$text=preg_replace("/^a/", "ha", $text);
	$text=preg_replace("/^e/", "he", $text);
	$text=preg_replace("/^i/", "hi", $text);
	$text=preg_replace("/^o/", "ho", $text);
	$text=preg_replace("/^u/", "hu", $text);
	$text=preg_replace("/^w/", "hw", $text);
	$text=preg_replace("/^y/", "hy", $text);
	return $text;
}

function trans_pos($text)
// translate grammatical info on the Welsh pages
// this is a bit of a kludge, but it works OK
// and the only alternative is to keep POS info in the table in both languages
{
	$text=preg_replace("/soft mutation/", "treiglad meddal", $text);
	$text=preg_replace("/nasal mutation/", "treiglad trwynol", $text);
	$text=preg_replace("/aspirate mutation/", "treiglad llaes", $text);
	$text=preg_replace("/addition/", "ychwanegiad", $text);
	$text=preg_replace("/initial h/", "h cychwynnol", $text);
	$text=preg_replace("/ of:/", " o:", $text);
	$text=preg_replace("/ to:/", " i:", $text);
	$text=preg_replace("/- ns /", "- eu ", $text);
	$text=preg_replace("/- np /", "- ell ", $text);
	$text=preg_replace("/\[m/", "[g", $text);
	$text=preg_replace("/\[f/", "[b", $text);
	$text=preg_replace("/\[mf/", "[gb", $text);
	$text=preg_replace("/- v /", "- be ", $text);
	$text=preg_replace("/present/", "presennol", $text);
	$text=preg_replace("/future/", "dyfodol", $text);
	$text=preg_replace("/past/", "gorffennol", $text);
	$text=preg_replace("/imperfect/", "amherffaith", $text);
	$text=preg_replace("/pluperfect/", "gorberffaith", $text);
	$text=preg_replace("/conditional/", "amodol", $text);
	$text=preg_replace("/imperative/", "gorchmynnol", $text);
	$text=preg_replace("/subjunctive/", "dibynnol", $text);
	$text=preg_replace("/singular/", "unigol", $text);
	$text=preg_replace("/plural/", "lluosog", $text);
	$text=preg_replace("/impersonal/", "amhersonol", $text);
	$text=preg_replace("/short/", "byr", $text);
	$text=preg_replace("/spoken/", "llafar", $text);
	return $text;
}

function lose_brackets($text)
// get rid of empty square brackets when there is no gender info
{
	$text=preg_replace("/\[\]/", "", $text);
	return $text;
}

function stupid_h($text)
{
	$text=preg_replace("/h cychwynnol mutation o:/", "ychwanegiad o h cychwynnol i:", $text);
	$text=preg_replace("/initial h mutation of:/", "addition of initial h to:", $text);
	return $text;
}

function gram_hint($text)
// give hints on the likely grammatical nature of an unknown word, based on its ending
{
	if (preg_match("/ol$/",$text)) {$hint="Many adjectives end in <strong>-ol</strong>";}
	if (preg_match("/us$/",$text)) {$hint="Many adjectives end in <strong>-us</strong>";}
	if (preg_match("/aidd$/",$text)) {$hint="Many adjectives end in <strong>-aidd</strong>";}
	if (preg_match("/io$/",$text)) {$hint="Many verb infinitives (also known as verbal nouns) end in <strong>-io</strong>";}
	return $hint;
}

function de_meddal($text)
// remove soft mutations so that the word can be looked up in Eurfa
// note that the order of these regex replacements is significant;
// letters in the regex must be placed before their occurrence as replacements
{
	$text=preg_replace("/^g/", "c", $text);
	$text=preg_replace("/^G/", "C", $text);
	$text=preg_replace("/^l/", "ll", $text);
	$text=preg_replace("/^L/", "Ll", $text);
	$text=preg_replace("/^r/", "rh", $text);
	$text=preg_replace("/^R/", "Rh", $text);
	$text=preg_replace("/^l([^l])/", "gl", $text);
	$text=preg_replace("/^L([^l])/", "Gl", $text);
	$text=preg_replace("/^r([^h])/", "gr", $text);
	$text=preg_replace("/^R([^h])/", "Gr", $text);
	$text=preg_replace("/^([aeoiuwyïŵŷ])/", "g$1", $text);
	$text=preg_replace("/^([AEOIUWYÏŴŶ])/", "G$1", $text);
	$text=preg_replace("/^f/", "[mb]", $text);
	$text=preg_replace("/^F/", "[MB]", $text);
	$text=preg_replace("/^b/", "p", $text);
	$text=preg_replace("/^B/", "P", $text);
	$text=preg_replace("/^d([^d])/", "t$1", $text);
	$text=preg_replace("/^D([^d])/", "T$1", $text);
	$text=preg_replace("/^dd/", "d", $text);
	$text=preg_replace("/^Dd/", "D", $text);
	return $text;
}

function de_trwynol($text)
// remove nasal mutations so that the word can be looked up in Eurfa
{
	$text=preg_replace("/^ngh/", "c", $text);
	$text=preg_replace("/^Ngh/", "C", $text);
	$text=preg_replace("/^ng[^h]/", "g", $text);
	$text=preg_replace("/^Ng[^h]/", "G", $text);
	$text=preg_replace("/^mh/", "p", $text);
	$text=preg_replace("/^Mh/", "P", $text);
	$text=preg_replace("/^m[^h]/", "b", $text);
	$text=preg_replace("/^M[^h]/", "B", $text);
	$text=preg_replace("/^nh/", "t", $text);
	$text=preg_replace("/^Nh/", "T", $text);
	$text=preg_replace("/^n[^h]/", "d", $text);
	$text=preg_replace("/^N[^h]/", "D", $text);
	return $text;
}

function de_llaes($text)
// remove aspirate mutations so that the word can be looked up in Eurfa
{
	$text=preg_replace("/^ch/", "c", $text);
	$text=preg_replace("/^Ch/", "C", $text);
	$text=preg_replace("/^ph/", "p", $text);
	$text=preg_replace("/^Ph/", "P", $text);
	$text=preg_replace("/^th/", "t", $text);
	$text=preg_replace("/^Th/", "T", $text);
	return $text;
}

function show_pos_en($text)
// replace Gramadóir tags with words
{
	if (preg_match("/V/","$text")) {$text="verb";}
	if (preg_match("/N/","$text")) {$text="noun";}
	if (preg_match("/A/","$text")) {$text="adjective";}
	if (preg_match("/R/","$text")) {$text="adverb";}
	if (preg_match("/C/","$text")) {$text="conjunction";}
	if (preg_match("/I/","$text")) {$text="exclamation";}
	if (preg_match("/S/","$text")) {$text="preposition";}
	if (preg_match("/P/","$text")) {$text="pronoun";}
	if (preg_match("/T/","$text")) {$text="definite article";}
	return $text;
}

function show_pos_cy($text)
// replace Gramadóir tags with words
{
	if (preg_match("/V/","$text")) {$text="berf";}
	if (preg_match("/N/","$text")) {$text="enw";}
	if (preg_match("/A/","$text")) {$text="ansoddair";}
	if (preg_match("/R/","$text")) {$text="adferf";}
	if (preg_match("/C/","$text")) {$text="cysylltair";}
	if (preg_match("/I/","$text")) {$text="ebychair";}
	if (preg_match("/S/","$text")) {$text="arddodiad";}
	if (preg_match("/P/","$text")) {$text="rhagenw";}
	if (preg_match("/T/","$text")) {$text="bannod benodol";}
	return $text;
}

function show_number_en($text)
// replace Gramadóir tags with words
{
	if (preg_match("/y/","$text")) {$text="plural";}
	if (preg_match("/n/","$text")) {$text="singular";}
	return $text;
}

function show_number_cy($text)
// replace Gramadóir tags with words
{
	if (preg_match("/y/","$text")) {$text="lluosog";}
	if (preg_match("/n/","$text")) {$text="unigol";}
	return $text;
}

function show_gender_en($text)
// replace Gramadóir tags with words
{
	if (preg_match("/m/","$text")) {$text="masculine";}
	if (preg_match("/f/","$text")) {$text="feminine";}
	if (preg_match("/mf/","$text")) {$text="masculine or feminine";}
	return $text;
}

function show_gender_cy($text)
// replace Gramadóir tags with words
{
	if (preg_match("/m/","$text")) {$text="gwrywaidd";}
	if (preg_match("/f/","$text")) {$text="benywaidd";}
	if (preg_match("/mf/","$text")) {$text="gwrywaidd neu fenywaidd";}
	return $text;
}

function show_mutation_en($text)
// replace Gramadóir tags with words
{
	if (preg_match("/0/","$text")) {$text="no mutation";}
	if (preg_match("/1/","$text")) {$text="soft mutation";}
	if (preg_match("/2/","$text")) {$text="aspirate mutation";}
	if (preg_match("/3/","$text")) {$text="nasal mutation";}
	return $text;
}

function show_mutation_cy($text)
// replace Gramadóir tags with words
{
	if (preg_match("/0/","$text")) {$text="dim treiglad";}
	if (preg_match("/1/","$text")) {$text="treiglad meddal";}
	if (preg_match("/2/","$text")) {$text="treiglad llaes";}
	if (preg_match("/3/","$text")) {$text="treiglad trwynol";}
	return $text;
}

function fix_K($stream)
// rewrite the punctuation so that it is properly spaced in the text
// at present, this is for web display, so we use &nbsp; and <br />
// this would need to be adjusted to spsp and nl/cr for non-web purposes
{
	$stream=preg_replace("/new_sentence/", "", $stream);
	$stream=preg_replace("/kl-new-br/", "<br />", $stream);
	
	$stream=preg_replace("/kl-in-dbl-q /", "\"", $stream);
	$stream=preg_replace("/ kl-ni-dbl-q/", "\"", $stream);
	
	$stream=preg_replace("/kl-in-sgl-q /", "'", $stream);
	$stream=preg_replace("/ kl-ni-sgl-q/", "'", $stream);
	
	$stream=preg_replace("/ \./", ".", $stream);
	$stream=preg_replace("/ \!/", "!", $stream);
	$stream=preg_replace("/ \?/", "?", $stream);
	
	$stream=preg_replace("/\( /", "(", $stream);
	$stream=preg_replace("/ \)/", ")", $stream);
	
	$stream=preg_replace("/\[ /", "[", $stream);
	$stream=preg_replace("/ \]/", "]", $stream);
	
	$stream=preg_replace("/\{ /", "{", $stream);
	$stream=preg_replace("/ \}/", "}", $stream);
	
	$stream=preg_replace("/ ,/", ",", $stream);
	$stream=preg_replace("/ :/", ":", $stream);
	$stream=preg_replace("/ ;/", ";", $stream);

	return $stream;
}

?>