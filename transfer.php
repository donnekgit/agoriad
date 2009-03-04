<?php
     
// Format the output from apertium-transfer

$sentence_no=1;

foreach ($transfer as $transfersentence)
{
  // split the output into sentences at the <sent> marker
  $transfersentence=preg_split("/<sent>\\\$\\}\\\$/", $transfersentence);
  // get rid of the last (empty) array entry
  array_pop($transfersentence);
    
  foreach ($transfersentence as $transferword)
  {
  // split each sentence into surface word chunks at the $}$ marker
  $transferword=preg_split("/\\\$\\}\\\$/", $transferword);
  // remove the ^ marker
  $transferword=preg_replace("/\\^/", "", $transferword);
  // differentiate slashes marking alternative lemmas from those marking the surface form
  $transferword=preg_replace("/>\\//", "> ~", $transferword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"3\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($transferword as $transferlemma)
    { 
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $transferlemma=preg_replace("/</", " [", $transferlemma);
      $transferlemma=preg_replace("/>/", "]", $transferlemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $transferlemma=preg_replace("/\[n\]/", "[noun]", $transferlemma);
      $transferlemma=preg_replace("/\[pr\]/", "[preposition]", $transferlemma);
      $transferlemma=preg_replace("/\[prn\]/", "[pronoun]", $transferlemma);
      $transferlemma=preg_replace("/\] \[/", ", ", $transferlemma);
      // put multiple lemmas on separate lines to ease reading
      //$transferlemma=preg_replace("/ ~/", "<br />", $transferlemma);
      $transferlemma=preg_replace("/\+/", "<br />+ ", $transferlemma);
      // make the output easier to read
      //$transferlemma=preg_replace("/\[/", " --- ", $transferlemma);
      $transferlemma=preg_replace("/\]/", "", $transferlemma);
            
      // split the strings - regexes don't work as well for this as the simpler explode()
      $transferlemma=explode('{', $transferlemma);
      $pos=explode('[', $transferlemma[0]);
      
      $transferlemma[1]=preg_replace("/\\\$ /", "<br />", $transferlemma[1]);
      $transferlemma[1]=preg_replace("/\[/", " --- ", $transferlemma[1]);
            
      // format and colourise the info; write it into the table
      $lttablerow= "<tr><td  width=\"20%\"><b>".$pos[0]."</b></td><td  width=\"40%\">".$pos[1]."</td><td  width=\"40%\">".colourme($transferlemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>