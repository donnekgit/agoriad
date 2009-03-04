<?php

//setlocale(LC_ALL, "en_GB.UTF-8");
header('Content-Type: text/html; charset=utf8');

include("./includes/fns.php");

$to_be_checked=$_POST['to_be_checked'];

// truncate the text input to 900 characters first, to stop people faffing about
$to_be_checked=strip_tags(substr($to_be_checked,0,900));

/********************************  ILAZKI ***************************************/
// On ilazki we need to change the following line to include stripslashes:
// $to_be_checked=trim(stripslashes($to_be_checked));
/********************************  ILAZKI ***************************************/
$to_be_checked=trim($to_be_checked);

// add a period at the end of the text if it is not already there, or if there is no ! or ? there
// otherwise the segmentation into sentences won't work
if (preg_match("/[.!?]$/", $to_be_checked) || empty($to_be_checked))
{
  $to_be_checked=$to_be_checked;
}
else
{
  $to_be_checked=$to_be_checked.".";
}

/********************************  ILAZKI ***************************************/
// The ilazki server has a slightly odd setup.  It seems to be using addslashes, ISO-8859, and short tags for php.
// Thebash shell is set to POSIX, so we need to change encoding before launching a new instance
// otherwise things like ô, ŷ etc will not be passed through correctly, hence the "export LANG=en_GB.utf8 &&"
// in the commands below.  For deployment, change "kevin" in the path below to "donnek".
// Note also the need to add stripslashes() above and below.
/********************************  ILAZKI ***************************************/

$bin_path="/home/kevin/local/bin/";
$lib_path="LD_LIBRARY_PATH=/home/kevin/local/lib"; 
$share_path="/home/kevin/local/share/apertium/apertium-cy-en/";

?>

<html xmlns="http‎://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf8">
  <title>Agoriad-cy - check output from apertium-cy</title>
  <link rel="stylesheet" href="agoriad1600.css" type="text/css" media="screen, projection">
  <!--[if IE]><link rel="stylesheet" href="ie.css" type="text/css" media="screen, projection"><![endif]-->
  <script type="text/javascript" src="accordion/javascript/prototype.js"></script>
  <script type="text/javascript" src="accordion/javascript/effects.js"></script>
  <script type="text/javascript" src="accordion/javascript/accordion.js"></script>
  <script type="text/javascript" src="agoriad.js"></script>
</head>

<body>

<!--Container for content-->
<div class="container">

<div class="span-7 colborder">
<form id="textform" action="import_text_en_1600.php" method="POST">
    <!--
    /********************************  ILAZKI ***************************************/
    // On ilazki we need to change the following line to include stripslashes:
    // <textarea name="to_be_checked"><?php echo $to_be_checked; ?></textarea><br />
    /********************************  ILAZKI ***************************************/
    -->
    <textarea name="to_be_checked"><?php echo $to_be_checked; ?></textarea><br />
    <input type="submit" name="submit" value="Translate!">
  </form>
  <br />
</div>

<div class="span-15 last">
  <h1>agoriad-cy 0.1</h1>
   <p><span class="alt">Agoriad</span> is a browser-based viewer for the output at different stages of Apertium processing.  This install is aimed at <span class="alt">apertium-cy</span> (the Apertium Welsh-English translator), but it could be easily adapted to reflect the processing stages in other Apertium pairs.  Several variants (<a href="import_text_en_950.php">950px</a>, <a href="import_text_en_1200.php">1200px</a>, <a href="import_text_en_1600.php">1600px</a>) allow for different widths of screens - wider screens allow more processing stages to be compared simultaneously.  <span class="alt">Agoriad</span> would not have been possible without Fran Tyers (for advice on Apertium), Olav Bjorkoy's <span class="alt"><a href="http://www.blueprintcss.org">blueprint-css</a></span> and Kevin Miller's <span class="alt"><a href="http://stickmanlabs.com/accordion">Accordion</a></span>.  To test, try typing <span class="alt">Mae'r gath yn yr ardd</span> (<em>The cat is in the garden</em>) into the box.</p>
</div>

<hr />
             
<div class="span-24">

  <!--lt-proc-->
  <div id="horizontal_container1" >
    <p class="horizontal_accordion_toggle">1<br />&nbsp;<br />l<br />t<br />-<br />p<br />r<br />o<br />c<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php 
          $lt_proc_output=exec('export LANG=en_GB.utf8 && echo "' . $to_be_checked . '" | ' . $bin_path."lt-proc " . $share_path."cy-en.automorf.bin", $ltproc);
          include("ltproc.php");
        ?>
      </p>
    </div>
  </div>
  
  <!--cg-proc-->
  <div id="horizontal_container2" >
    <p class="horizontal_accordion_toggle">2<br />&nbsp;<br />c<br />g<br />-<br />p<br />r<br />o<br />c<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php 
          $cg_proc_output=exec('export LANG=en_GB.utf8 && echo "' . $lt_proc_output . '" | ' . $bin_path."cg-proc " . $share_path."cy-en.cg.bin", $cgproc);
          include("cgproc.php");
        ?>
      </p>
    </div>
  </div>
    
  <!--tagger-->
  <div id="horizontal_container3" >
    <p class="horizontal_accordion_toggle">3<br />&nbsp;<br />t<br />a<br />g<br />g<br />e<br />r<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php
          $tagger_output=exec('export LANG=en_GB.utf8 && echo "' . $cg_proc_output . '" | ' . $bin_path."apertium-tagger -g " . $share_path."cy-en.prob", $tagger);
          include("tagger.php");
        ?>
      </p>
    </div>
  </div>
  
  <!--pretransfer-->
  <div id="horizontal_container4" >
    <p class="horizontal_accordion_toggle">4<br />&nbsp;<br />p<br />r<br />e<br />t<br />r<br />a<br />n<br />s<br />f<br />e<br />r<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php
          $pretransfer_output=exec('export LANG=en_GB.utf8 && echo "' . $tagger_output . '" | ' . $bin_path."apertium-pretransfer ", $pretransfer); 
          include("pretransfer.php");
        ?>
      </p>
    </div>
  </div>
  
  <!--transfer-->
  <div id="horizontal_container5" >
    <p class="horizontal_accordion_toggle">5<br />&nbsp;<br />t<br />r<br />a<br />n<br />s<br />f<br />e<br />r<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php 
          $transfer_output=exec('export LANG=en_GB.utf8 && echo "' . $pretransfer_output . '" | ' . $bin_path."apertium-transfer " .  $share_path."apertium-cy-en.cy-en.t1x " . $share_path."cy-en.t1x.bin " . $share_path."cy-en.autobil.bin", $transfer); 
          include("transfer.php");
        ?>
      </p>
    </div>
  </div>
  
  <!--interchunk-->
  <div id="horizontal_container6">
    <p class="horizontal_accordion_toggle">6<br />&nbsp;<br />i<br />n<br />t<br />e<br />r<br />c<br />h<br />u<br />n<br />k<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php
          $interchunk_output=exec('export LANG=en_GB.utf8 && echo "' . $transfer_output . '" | ' . $bin_path."apertium-interchunk " . $share_path."apertium-cy-en.cy-en.t2x " . $share_path."cy-en.t2x.bin", $interchunk);
          include("interchunk.php");
        ?>
      </p>
    </div>
  </div>
  
  <!--postchunk-->
  <div id="horizontal_container7">
    <p class="horizontal_accordion_toggle">7<br />&nbsp;<br />p<br />o<br />s<br />t<br />c<br />h<br />u<br />n<br />k<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php
          $postchunk_output=exec('export LANG=en_GB.utf8 && echo "' . $interchunk_output . '" | ' . $bin_path."apertium-postchunk " . $share_path."apertium-cy-en.cy-en.t3x " . $share_path."cy-en.t3x.bin" , $postchunk); 
          include("postchunk.php");
        ?>
      </p>
    </div>
 </div> 
  
  <!--lt-proc morphological generation-->
  <div id="horizontal_container8">
    <p class="horizontal_accordion_toggle">8<br />&nbsp;<br />l<br />t<br />-<br />p<br />r<br />o<br />c<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php
          $lt_proc2_output=exec('export LANG=en_GB.utf8 && echo "' . $postchunk_output . '" | ' . $bin_path."lt-proc -g " . $share_path."cy-en.autogen.bin", $ltproc2); 
           include("ltproc2.php");
        ?>
      </p>
    </div>
  </div>
  
  <!--lt-proc post-generation-->
  <div id="horizontal_container9">
    <p class="horizontal_accordion_toggle">9<br />&nbsp;<br />l<br />t<br />-<br />p<br />r<br />o<br />c<br /></p>
    <div class="horizontal_accordion_content">
      <p>
        <?php
          $lt_proc3_output=exec('export LANG=en_GB.utf8 && echo "' . $lt_proc2_output . '" | ' . $bin_path."lt-proc -p " . $share_path."cy-en.autopgen.bin", $ltproc3); 
          include("ltproc3.php");
        ?>
      </p>
    </div>
  </div>
     
</div> 

</div> <!-- end container -->

</body>
</html>
