<?php
     
// Format the output from apertium-tagger
// This is largely the same as lgproc.php, except for the variable names, so they could probably be combined.

$sentence_no=1;

foreach ($tagger as $taggersentence)
{
  // split the output into sentences at the <sent> marker
  $taggersentence=preg_split("/<sent>\\\$/", $taggersentence);
  // get rid of the last (empty) array entry
  array_pop($taggersentence);
    
  foreach ($taggersentence as $taggerword)
  {
  // split each sentence into surface word chunks at the $ marker
  $taggerword=preg_split("/\\\$/", $taggerword);
  // remove the ^ marker
  $taggerword=preg_replace("/\\^/", "", $taggerword);
  // differentiate slashes marking alternative lemmas from those marking the surface form - required?
  $taggerword=preg_replace("/>\\//", "> ~", $taggerword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"2\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($taggerword as $taggerlemma)
    { 
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $taggerlemma=preg_replace("/</", " [", $taggerlemma);
      $taggerlemma=preg_replace("/>/", "]", $taggerlemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $taggerlemma=preg_replace("/\[n\]/", "[noun]", $taggerlemma);
      $taggerlemma=preg_replace("/\[pr\]/", "[preposition]", $taggerlemma);
      $taggerlemma=preg_replace("/\[prn\]/", "[pronoun]", $taggerlemma);
      $taggerlemma=preg_replace("/\] \[/", ", ", $taggerlemma);
      
      // split lemma off from POS info
      $taggerlemma=preg_split("/ \[/", $taggerlemma);
      // remove the trailing }
      $taggerlemma=preg_replace("/\]/", "", $taggerlemma);
      
      // put multiple lemmas on separate lines to ease reading - required for tagger?
      $taggerlemma=preg_replace("/ ~/", "<br />", $taggerlemma);
      $taggerlemma=preg_replace("/\+/", "<br />+ ", $taggerlemma);
      
      // format the surface word, and colourise the lemmas; write them into the table
      $lttablerow= "<tr><td  width=\"30%\"><b>".$taggerlemma[0]."</b></td><td>".colourme($taggerlemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>