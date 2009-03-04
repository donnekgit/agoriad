<?php
     
// Format the output from cg-proc
// This is exactly the same as lgproc.php, except for the bvariable names, so they could probably be combined.
// Need to check with FT what exactly cg-proc does that is different from lt-proc.

$sentence_no=1;

foreach ($cgproc as $cgsentence)
{
  // split the output into sentences at the <sent> marker
  $cgsentence=preg_split("/<sent>\\\$/", $cgsentence);
  // get rid of the last (empty) array entry
  array_pop($cgsentence);
    
  foreach ($cgsentence as $cgword)
  {
  // split each sentence into surface word chunks at the $ marker
  $cgword=preg_split("/\\\$/", $cgword);
  // remove the ^ marker
  $cgword=preg_replace("/\\^/", "", $cgword);
  // differentiate slashes marking alternative lemmas from those marking the surface form
  $cgword=preg_replace("/>\\//", "> %", $cgword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"2\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($cgword as $cglemma)
    { 
      // split each surface word chunk at the following slash
      $cglemma=preg_split("/\\//", $cglemma);
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $cglemma=preg_replace("/</", " {", $cglemma);
      $cglemma=preg_replace("/>/", "}", $cglemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $cglemma=preg_replace("/{n}/", "{noun}", $cglemma);
      $cglemma=preg_replace("/{pr}/", "{preposition}", $cglemma);
      $cglemma=preg_replace("/{prn}/", "{pronoun}", $cglemma);
      $cglemma=preg_replace("/} {/", ", ", $cglemma);
      // put multiple lemmas on separate lines to ease reading
      $cglemma=preg_replace("/ %/", "<br />", $cglemma);
      $cglemma=preg_replace("/\+/", "<br />+ ", $cglemma);
      
      // format the surface word, and colourise the lemmas; write them into the table
      $lttablerow= "<tr><td  width=\"30%\"><b>".$cglemma[0]."</b></td><td>".colourme($cglemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>