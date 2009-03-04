<?php
     
// Format the output from apertium-interchunk

$sentence_no=1;

foreach ($interchunk as $interchunksentence)
{
  // split the output into sentences at the <sent> marker
  $interchunksentence=preg_split("/<sent>\\\$\\}\\\$/", $interchunksentence);
  // get rid of the last (empty) array entry
  array_pop($interchunksentence);
    
  foreach ($interchunksentence as $interchunkword)
  {
  // split each sentence into surface word chunks at the $}$ marker
  $interchunkword=preg_split("/\\\$\\}\\\$/", $interchunkword);
  // remove the ^ marker
  $interchunkword=preg_replace("/\\^/", "", $interchunkword);
  // differentiate slashes marking alternative lemmas from those marking the surface form
  $interchunkword=preg_replace("/>\\//", "> ~", $interchunkword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"3\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($interchunkword as $interchunklemma)
    { 
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $interchunklemma=preg_replace("/</", " [", $interchunklemma);
      $interchunklemma=preg_replace("/>/", "]", $interchunklemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $interchunklemma=preg_replace("/\[n\]/", "[noun]", $interchunklemma);
      $interchunklemma=preg_replace("/\[pr\]/", "[preposition]", $interchunklemma);
      $interchunklemma=preg_replace("/\[prn\]/", "[pronoun]", $interchunklemma);
      $interchunklemma=preg_replace("/\] \[/", ", ", $interchunklemma);
      // put multiple lemmas on separate lines to ease reading
      //$interchunklemma=preg_replace("/ ~/", "<br />", $interchunklemma);
      $interchunklemma=preg_replace("/\+/", "<br />+ ", $interchunklemma);
      // make the output easier to read
      //$interchunklemma=preg_replace("/\[/", " --- ", $interchunklemma);
      $interchunklemma=preg_replace("/\]/", "", $interchunklemma);
            
      // split the strings - regexes don't work as well for this as the simpler explode()
      $interchunklemma=explode('{', $interchunklemma);
      $pos=explode('[', $interchunklemma[0]);
      
      $interchunklemma[1]=preg_replace("/\\\$ /", "<br />", $interchunklemma[1]);
      $interchunklemma[1]=preg_replace("/\[/", " --- ", $interchunklemma[1]);
            
      // format and colourise the info; write it into the table
      $lttablerow= "<tr><td  width=\"20%\"><b>".$pos[0]."</b></td><td  width=\"40%\">".$pos[1]."</td><td  width=\"40%\">".colourme($interchunklemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>