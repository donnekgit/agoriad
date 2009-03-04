<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    
    
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">


<head>
	<title>Apertium alpha-testing</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
	/*<![CDATA[*/
		div 	{ background-color : white; padding: 2px; border: black 2px solid; position: relative;}
		body 	{ background-color: white; padding: 2px; margin: 2px; }
		.banner	{ background-color: white; margin: 2px; padding: 2px; }
		.tranbox{ background-color: white; margin: 2px; padding: 2px; float: left; width: 33%; }
		.outbox { background-color: white; margin: 2px; padding: 2px; float: right; width: 64%; }
		.langchooser { background-color: white; margin: 2px; padding: 2px; width: 96%; text-align: right; align: center; border: 0px; font-size: 80% }
	/*]]>*/
	</style>
</head>


<body>


<div class="langchooser">
<a href="index.php?lang=af_ZA">af</a> ·
<a href="index.php?lang=cy_GB">cy</a> ·
<a href="index.php?lang=en_GB">en</a> ·
<a href="index.php?lang=ro_RO">ro</a> ·
<a href="index.php?lang=bs_BA">sh</a>
</div>



  <div> 
  <?php
  // set up language choice options and print a reminder
	setlocale(LC_ALL, 0);
	if($_GET["lang"]) {
		$newLocale = setlocale(LC_ALL, $_GET["lang"] . '.UTF-8');
	} else {
		$newLocale = setlocale(LC_ALL, 'en_GB.UTF-8');
	}
	$domain = 'messages';
	bindtextdomain($domain, "./locale");
	textdomain($domain);
/*
	print _("This page allows you to test the pre-alpha (from SVN) versions of Apertium data") . ". ";
	print _("These data will probably not work more often than not") . ". ";
	print _("Often the data are woefully incomplete and they are not supported in any way") . ".";
*/  
  ?>
  </div> 
  


    <div class="tranbox">
      <form action=<?php print '"index.php?lang=' . $_GET["lang"] .'"'; ?> method="post">
        <p>
        
             
	<textarea cols="43" rows="9" name="inputarea"><?php print str_replace('\\', '', $_POST["inputarea"]); ?></textarea><br/>
  
  
        <select name="direction">
	<?php
    // this section gets the list of language directions as given in the file "enabled" and displays it
    // file() reads each line of the file into an array
    // "enabled" includes each language pair direction, the language names, and the encoding
		$enabled = file('enabled');

		for($i = 0; $i < sizeof($enabled); $i++) {
			if($enabled[$i][0] == '#') {
				continue;
			}

			$line = explode(',', $enabled[$i]);
			// pair,direction,source,target

			$d_state = $_POST["direction"];	
			if(isset($_GET["direction"])) {
				$d_state = $_GET["direction"];
			}

			if($d_state) { // maintain state
				print '<option value="' . $line[1] . '" ';
				if($line[1] == $d_state) {
					print 'selected';
				}
				print '>' . _($line[2]) . ' &#8594; ' . _(trim($line[3])) . '</option>';
			} else {
				print '<option value="' . $line[1] . '">' . _($line[2]) . ' &#8594; ' . _(trim($line[3])) . '</option>';
			}
		}
	?>
	</select><br />
  
  
	<label for="marcar">
	  	<input id="marcar" value="1" name="marcar" type="checkbox" title="<?php print _('Check the box to mark unknown words'); ?>" <?php if($_POST["marcar"]) print 'checked'; ?> /> 
		<?php print _('Mark unknown words'); ?>
	</label> <br />
  
  
	<label for="interm">
	  	<input id="interm" value="1" name="interm" type="checkbox" title="<?php print _('Check the box to print the intermediate representation'); ?>" <?php if($_POST["interm"]) print 'checked'; ?> />
		<?php print _('Print intermediate representation'); ?>
	</label>
  

	<input type="hidden" value="true" name="mangle"/><br/>
	<input type="submit" value="<?php print _('Translate'); ?>"/>
	</p>
  
  
      </form>
      
      <!-- What the symbols mean -->
      <hr style="border: black 1px solid;"/>
      <!--<p style="font-size: 8pt;">-->
      <p>
      <span style='color: #990000;'><code>*</code></span> &mdash; <?php print _('Word not found in source'); ?>.<br/>
      <span style='color: #990000;'><code>#</code></span> &mdash; <?php print _('Word found but no inflection'); ?>.<br/>
      <span style='color: #990000;'><code>@</code></span> &mdash; <?php print _('Word not found in target'); ?>.
      </p>
      <hr style="border: black 1px solid;"/>
      <!--<p style="font-size: 8pt;">-->
      <p>
      <span style='color: #009900;'><code>^ $</code></span> &mdash; <?php print _('Lexical unit start/stop'); ?><br/>
      <span style='color: #999900;'><code>{ }</code></span> &mdash; <?php print _('Chunk start/stop'); ?><br/>
      </p>
      
      <!--  SVG map of Apertium progress - not relevant here
      <div align="center">
      <p style="align: center;">
      <a href="graph.svg" target="_new">
      <img src="graph.png" width="300" border="0"/>
      </a>
      </p>
      </div>
      -->
      
      
    </div> <!-- e -->
    
    

    <div class="outbox"> <!-- b -->
    <?php
    // if the form has been submitted with something in it
    	if($_POST['mangle']) {
		include('Apertium.php');
    
    // the following selects the encoding to be used for the input text
		$enabled = file('enabled');
		$filesuf = '';
		$encoding = 'latin1'; // FT default
    // why have this as the default?  all the languages now use utf-8  

		for($i = 0; $i < sizeof($enabled); $i++) {
			$line = explode(',', $enabled[$i]);
			// FT pair,direction,source,target
			if(strstr($line[1], $_POST['direction'])) {
				$filesuf = $line[0];
				$encoding = trim($line[4]);
			}
		}

    // set up an Apertium object with the relevant language pair,direction and encoding
		$translator = new Apertium($filesuf, $_POST['direction'], $encoding);
	  
    // arrange to print intermediate representation if that is ticked
		if($_POST["interm"]) {
			$options['interm'] = true;
		}
    // arrange to mark unknown words if that is ticked
		if($_POST["marcar"]) {
			$options['marcar'] = true;
		}

		$options['display'] = true;

    // pass the input data (as text, not html) + options to the Apertium object
		$translator->translate($_POST['inputarea'], $options, 'txt');
	}
    ?>
    </div> <!-- e -->


</body>
</html>
