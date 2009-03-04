<?php //header('Content-Type: text/html; charset=utf-8'); ?>

<html xmlns="http‎://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Translate Welsh text</title>
<link rel="stylesheet" href="screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="print.css" type="text/css" media="print">    
<!--[if IE]><link rel="stylesheet" href="ie.css" type="text/css" media="screen, projection"><![endif]-->
<script type="text/javascript" src="show_hide.js"></script>
</head>
<body>

<!--Container for content-->
<div class="container">

<div class="span-24">
  <a href="import_text_en.php"><img src="images/banner.png" width="950" height="130" /></a>
</div>

<div class="span-24">
  <h1>apertium-cy 0.1</h1>
</div>

<div class="span-11 colborder">
  <h3 class="alt">Apertium SVN: revision 6001</h3>  
    <form id="textform" action="import_text_en.php" method="POST">
      <textarea name="to_be_checked" cols="40" rows="15"></textarea><br />
      <input type="submit" name="submit" value="Translate!">
    </form>
  <br />
  <p><span class="alt">Remember!</span>  If only a piece of the sample, untranslated, appears after you press <em>Translate!</em>, there is a linebreak, semicolon, or bracket in your text.  You have to get rid of it first.</p>
</div>
                       
<div class="span-12 last">

  <h3 class="right"><a href="receive.php">Cymraeg</a></h3>
  <hr />

  <p>Type or paste your chosen text into the box, and then click <span class="alt">Translate!</span>  You can also use the sample passages below the box - click on the heading to open the passage.  Note that anything entered in the box will be truncated to 900 characters.</p>
  
  <p>Words not currently in the dictionary will be marked with an asterisk.</p>

  <h4>Limitations</h4>
          
  <p>Since <span class="alt">apertium-cy</span> is still under development, please note:
  <ul>
    <li>Slang or spoken (informal) language will not translate well in this release.</li>
    <li>Don't use spoken abbreviations - eg use <strong>dyna</strong> instead of <strong>'na</strong>.</li>
    <li>Don't use linebreaks (the interface doesn't like them!).  Enter everything (even poetry) on one line.</li>
    <li>Don't use semicolons (;) or brackets.  Replace them with commas, or begin a new sentence.</li>
    <li>Capitalisation and quoting in the translation is currently handled incorrectly.</li>
  </ul>
  </p>
  
</div>  

</div> <!-- end container -->

</body>
</html>
