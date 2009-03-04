<?php
     
// Format the output from lt-proc

$sentence_no=1;

foreach ($ltproc as $ltsentence)
{
  // split the output into sentences at the <sent> marker
  $ltsentence=preg_split("/<sent>\\\$/", $ltsentence);
  // get rid of the last (empty) array entry
  array_pop($ltsentence);
    
  foreach ($ltsentence as $ltword)
  {
  // split each sentence into surface word chunks at the $ marker
  $ltword=preg_split("/\\\$/", $ltword);
  // remove the ^ marker
  $ltword=preg_replace("/\\^/", "", $ltword);
  // differentiate slashes marking alternative lemmas from those marking the surface form
  $ltword=preg_replace("/>\\//", "> ~", $ltword);
  
  // start building a html table
  echo "<table><tr><td colspan=\"2\">Sentence " . $sentence_no . "</td></tr>";
  $sentence_no++;
    
    foreach ($ltword as $ltlemma)
    { 
      // split each surface word chunk at the following slash
      $ltlemma=preg_split("/\\//", $ltlemma);
      // replace angle brackets, so that we can see them without having to use htmlentities()
      $ltlemma=preg_replace("/</", " [", $ltlemma);
      $ltlemma=preg_replace("/>/", "]", $ltlemma);
      // we need to write these out in full to avoid highlighting applying to (eg) "n"s inside words
      // most (except n) are currently written back by the colourme function, but they don't need to be
      // n needs to come before prn
      $ltlemma=preg_replace("/\[n\]/", "[noun]", $ltlemma);
      $ltlemma=preg_replace("/\[pr\]/", "[preposition]", $ltlemma);
      $ltlemma=preg_replace("/\[prn\]/", "[pronoun]", $ltlemma);
      $ltlemma=preg_replace("/\] \[/", ", ", $ltlemma);
      
      //mark multiwords
      $ltlemma=preg_replace("/\# /", "<br /><em>multiword with:</em> ", $ltlemma);
      
      // put multiple lemmas on separate lines to ease reading
      $ltlemma=preg_replace("/ ~/", "<br />", $ltlemma);
      $ltlemma=preg_replace("/\+/", "<br />+ ", $ltlemma);
      // make the output easier to read
      $ltlemma=preg_replace("/\[/", " --- ", $ltlemma);
      $ltlemma=preg_replace("/\]/", "", $ltlemma);
      
      // format the surface word, and colourise the lemmas; write them into the table
      $lttablerow= "<tr><td  width=\"30%\"><b>".$ltlemma[0]."</b></td><td>".colourme($ltlemma[1])."</td></tr>";
      echo $lttablerow;
    }
    
    // close off the table
    echo "</table>";
  
  }
  
}

?>