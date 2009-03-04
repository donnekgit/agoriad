<?php
     
// Format the output from the second run of lt-proc

$sentence_no=1;

foreach ($ltproc2 as $lt2sentence)
{
  //add a sentence-delimiter
  $lt2sentence=preg_replace("/([.!?])/", "$1~~~", $lt2sentence);
  // split the output into sentences at the sentence-delimiter
  $lt2sentence=preg_split("/~~~/", $lt2sentence);
  // get rid of the last (empty) array entry
  array_pop($lt2sentence);

  foreach ($lt2sentence as $lt2word)
  {
    // put the sentences into a html table, capitalising each
    echo "<table><tr><td>Sentence " . $sentence_no . "</td></tr>";
    $sentence_no++;
    echo "<tr><td>" . ucfirst(trim($lt2word)) . "</td></tr></table>";
   }
  
}

?>