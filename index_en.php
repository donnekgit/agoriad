<?php //header('Content-Type: text/html; charset=utf-8'); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  
<head>
  <title>Translate Welsh to English</title>
  <meta name="GENERATOR" content="Quanta Plus" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="screen.css" type="text/css" media="screen, projection">
  <link rel="stylesheet" href="print.css" type="text/css" media="print">    
  <!--[if IE]><link rel="stylesheet" href="ie.css" type="text/css" media="screen, projection"><![endif]-->
</head>
<body>
  
<!--Container for content-->  
<div class="container">

<div class="span-24">
  <img src="images/banner.png" width="950" height="130" />
</div>

<div class="span-24">
  <p/>
  <h2 class="alt">Welcome to apertium-cy, the first free (GPL) automatic translator for Welsh!</h2>
  <h3><a href="receive_en.php">Click here to test the Welsh-English translator!</a></h3>
</div>

<div class="span-24">
  <p/>
</div>
    
<div class="span-18 colborder">
  
  <h4>What is <span class="alt">apertium-cy</span>?</h4>
   
  <p><span class="alt">apertium-cy</span> is a free (GPL) Welsh-to-English translator (an English-to-Welsh translator is also being developed).  "Free (GPL)" means that it is licenced under the <a href="http://www.fsf.org">Free Software Foundation</a>'s General Public License, so not only is it free of charge, but you also have the freedom to study it, modify it and share it without breaking the law.  Free (GPL) software is now being used all over the world by governments, public sector bodies, companies and individuals, because of the many benefits it offers.</p>
  
  <h4>The Apertium platform</h4>
  
  <p><span class="alt">apertium-cy</span> is part of a larger machine translation project called <a href="http://apertium.org">Apertium</a>, producing software to convert text in one language into text in a different language.  Apertium was developed by Mikel Forcada's <a href="http://transducens.dlsi.ua.es">Transducens research group</a> at the University of Alacant and <a href="http://www.prompsit.com/en">Prompsit Language Engineering</a> in the Region of Valencia in Spain.  So far, the multinational Apertium team has released automatic translators for 14 other language pairs (Catalan-English, Spanish-French, etc), and is working on a dozen others.  <span class="alt">apertium-cy</span> is the first Apertium translator to be released that does not include a Romance language such as Catalan or Spanish.  The Apertium software can be <a href="http://www.apertium.org/?id=downloading&lang=en">downloaded</a> from the Apertium site, which also contains a <a href="http://wiki.apertium.org/wiki/Main_Page">wiki</a> giving information on installation, etc.</p>
  
  <p><span class="alt">apertium-cy</span> was developed over the past 9 months by Francis Tyers and Kevin Donnelly.  Francis, the lead developer, is part of the Transducens research group, and Kevin has been working independently on free (GPL) Welsh-language software for the past 5 years.  <span class="alt">apertium-cy</span> builds on two of his projects, <a href="http://www.eurfa.org.uk">Eurfa</a> (a Welsh dictionary) and <a href="http://ww.klebran.org.uk">Klebran</a> (a grammar-checker based on Kevin Scannell's Irish grammar-checker, <a href="http://borel.slu.edu/gramadoir">Gramadóir</a>).  <span class="alt">apertium-cy</span> currently contains around 10,000 words, and about 150 grammatical rules.</p>
  
  <p>An important benefit of using Apertium is that the work done on other language pairs can be re-used to give us a headstart when we come to build other translators for Welsh - see <a href="http://xixona.dlsi.ua.es/~carmentano/sepln2008.pdf">this paper</a> (in Spanish) for more details.  For instance, the Spanish translators could be used to help create a Welsh-Spanish translator.  If there are any Welsh-speakers in Patagonia who long for a Welsh-Spanish translator, we'd be glad to hear from them!</p>
  
  <h4>How good is <span class="alt">apertium-cy</span>?</h4>
  
  <p>Don't expect this initial version of <span class="alt">apertium-cy</span> to produce perfect translations!  On <a href="receive.php">the test page</a> there are 21 sample passages to try.  These short passages cover poetry, official statements, novels, newspaper articles and non-fiction, and have not been edited except for punctuation.  They give a good indication of <span class="alt">apertium-cy</span>'s current strengths and weaknesses.  Alternatively, you can type in your own passages, but note the limitations listed on <a href="receive.php">the test page</a>.</p>
  
  <p><span class="alt">apertium-cy</span> is being continuously improved, and over the next few months we hope to refine the grammatical rules (particularly for subordinate clauses), expand the dictionaries, and release an initial version of a similar English-to-Welsh translator.  One of the key tenets of free (GPL) software development is that you should release software as soon as it works, so that you can take advantage of user feedback to improve the software.</p>
  
  <h4>What can I use <span class="alt">apertium-cy</span> for?</h4>
  
  <p><span class="alt">apertium-cy</span> is currently good enough for you to get the gist of a passage (provided there are not too many unknown words in it), so it may be useful for:
    <ul>
      <li>Welsh learners who want to get an overview of the subject matter of a text before they start studying it in more detail</li>
      <li>researchers and business people who want to keep an eye on Welsh media reports in their business area.</li>
    </ul></p>
    
    <p>In the longer term, and especially when the English-to-Welsh translator is available, <span class="alt">apertium-cy</span> could be used by public sector bodies, companies, voluntary groups, etc to provide a "first-pass" translation of publicity material, documents, etc, thus improving the productivity of human translators.</p>
  
  <h4>How can I help?</h4>
  
  <p>You can give us feedback on our progress so far.  You can help test new versions.  You can add words to the dictionary, and help to develop new rules to improve the grammar conversion (this will be especially important for the English-to-Welsh translator).</p>
  
  <p>If you are a public body or company that produces bilingual text, you can help by giving us digital copies of this.  We don't want to republish it, we just want to store the sentences from it in a database which will help us generate tags and rules.</p>
  
  <p>You can also help by asking your Assembly Member to help ensure that resources that receive public funds are made available under a free licence.  The sad thing is that the development of these translators could proceed much more quickly if we didn't have to create freely-distributable word lists for them from scratch.  Public money has gone into compiling Welsh dictionaries and lists of terms, and yet <span class="alt">apertium-cy</span> and similar projects cannot use these because they are not available under terms which allow them to be freely redistributed.  Every minute we spend adding words is a minute we can't spend writing software.  It would be a tremendous help to our work if the Welsh Language Board could look again at this issue - after all, if developers in Spain and the USA are prepared to spend time on Welsh, we should surely give them all the help we can!</p>
  
</div>


<div class="span-5 last">
  
  <h3 class="right"><a href="index.php">Cymraeg</a></h3>
  
  <hr />
  
  <h3 class="alt">Contact us</h3>
  
  <img src="images/email.png" width="115" height="17" />
  <p/>
  
  <hr />
  
  <h3 class="alt">Other sites</h3>
  
  <p><a href="http://apertium.org">Apertium</a></p>
  
  <p><a href="http://www.eurfa.org.uk">Eurfa dictionary</a></p>
  
  <p><a href="http://www.klebran.org.uk">Klebran grammar checker</a></p>
  
  <p><a href="http://www.konjugator.org.uk">Konjugator for Welsh verbs</a></p>
  
  <hr />
  
  <h3 class="alt">Eurfa for Stardict</h3>
  
  <p><a href="EurfaEW.tar.gz">English-Welsh</a></p>
  
  <p><a href="EurfaWE.tar.gz">Welsh-English</a></p>


</div>

<div class="span-24">
  <p/>
</div>

<div class="span-24 footer">
  Copyright 2008 Apertium team and Kevin Donnelly
</div>

</div><!-- end container -->

</body>
</html>