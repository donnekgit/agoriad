<?php
     
// Format the output from the second run of lt-proc

$sentence_no=1;

foreach ($ltproc3 as $lt3sentence)
{
  //add a sentence-delimiter
  $lt3sentence=preg_replace("/([.!?])/", "$1~~~", $lt3sentence);
  // split the output into sentences at the sentence-delimiter
  $lt3sentence=preg_split("/~~~/", $lt3sentence);
  // get rid of the last (empty) array entry
  array_pop($lt3sentence);

  foreach ($lt3sentence as $lt3word)
  {
    // put the sentences into a html table, capitalising each
    echo "<table><tr><td>Sentence " . $sentence_no . "</td></tr>";
    $sentence_no++;
    echo "<tr><td>" . ucfirst(trim($lt3word)) . "</td></tr></table>";
   }
  
}

?>