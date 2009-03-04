<?php
     
// Format the output from apertium-postchunk

$sentence_no=1;

foreach ($postchunk as $postchunksentence)
{
  // split the output into sentences at the <sent> marker
  $postchunksentence=preg_split("/<sent>\\\$/", $postchunksentence);
  // get rid of the last (empty) array entry
  array_pop($postchunksentence);
    
  foreach ($postchunksentence as $postchunkword)
  {
  // split each sentence into surface word chunks at the $ marker
  $postchunkword=preg_split("/\\\$/", $postchunkword);
  // remove the ^ marker
  $postchunkword=preg_replace("/\\^/", "", $postchunkword);
  // differentiate slashes marking alternative lemmas from those marking the surface form - required?
  $postchunkword=preg_replace("/>\\//", "> ~", $postchunkword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"2\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($postchunkword as $postchunklemma)
    { 
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $postchunklemma=preg_replace("/</", " [", $postchunklemma);
      $postchunklemma=preg_replace("/>/", "]", $postchunklemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $postchunklemma=preg_replace("/\[n\]/", "[noun]", $postchunklemma);
      $postchunklemma=preg_replace("/\[pr\]/", "[preposition]", $postchunklemma);
      $postchunklemma=preg_replace("/\[prn\]/", "[pronoun]", $postchunklemma);
      $postchunklemma=preg_replace("/\] \[/", ", ", $postchunklemma);
      
      // split lemma off from POS info
      $postchunklemma=preg_split("/ \[/", $postchunklemma);
      // remove the trailing }
      $postchunklemma=preg_replace("/\]/", "", $postchunklemma);
      
      // put multiple lemmas on separate lines to ease reading - required for tagger?
      $postchunklemma=preg_replace("/ ~/", "<br />", $postchunklemma);
      $postchunklemma=preg_replace("/\+/", "<br />+ ", $postchunklemma);
      
      // format the surface word, and colourise the lemmas; write them into the table
      $lttablerow= "<tr><td  width=\"30%\"><b>".$postchunklemma[0]."</b></td><td>".colourme($postchunklemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>