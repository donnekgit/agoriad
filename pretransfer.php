<?php
     
// Format the output from apertium-pretransfer
// This is largely the same as lgproc.php, except for the bvariable names, so they could probably be combined.

$sentence_no=1;

foreach ($pretransfer as $pretransfersentence)
{
  // split the output into sentences at the <sent> marker
  $pretransfersentence=preg_split("/<sent>\\\$/", $pretransfersentence);
  // get rid of the last (empty) array entry
  array_pop($pretransfersentence);
    
  foreach ($pretransfersentence as $pretransferword)
  {
  // split each sentence into surface word chunks at the $ marker
  $pretransferword=preg_split("/\\\$/", $pretransferword);
  // remove the ^ marker
  $pretransferword=preg_replace("/\\^/", "", $pretransferword);
  // differentiate slashes marking alternative lemmas from those marking the surface form - required?
  $pretransferword=preg_replace("/>\\//", "> ~", $pretransferword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"2\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($pretransferword as $pretransferlemma)
    { 
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $pretransferlemma=preg_replace("/</", " [", $pretransferlemma);
      $pretransferlemma=preg_replace("/>/", "]", $pretransferlemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $pretransferlemma=preg_replace("/\[n\]/", "[noun]", $pretransferlemma);
      $pretransferlemma=preg_replace("/\[pr\]/", "[preposition]", $pretransferlemma);
      $pretransferlemma=preg_replace("/\[prn\]/", "[pronoun]", $pretransferlemma);
      $pretransferlemma=preg_replace("/\] \[/", ", ", $pretransferlemma);
      
      // split lemma off from POS info
      $pretransferlemma=preg_split("/ \[/", $pretransferlemma);
      // remove the trailing }
      $pretransferlemma=preg_replace("/\]/", "", $pretransferlemma);
      
      // put multiple lemmas on separate lines to ease reading - required for tagger?
      $pretransferlemma=preg_replace("/ ~/", "<br />", $pretransferlemma);
      $pretransferlemma=preg_replace("/\+/", "<br />+ ", $pretransferlemma);
      
      // format the surface word, and colourise the lemmas; write them into the table
      $lttablerow= "<tr><td  width=\"30%\"><b>".$pretransferlemma[0]."</b></td><td>".colourme($pretransferlemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>