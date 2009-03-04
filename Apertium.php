<?php
	class Apertium {

		public $instroot = '';
		public $bindir = '';
		public $srcroot = '';
		public $pair = '';
		public $direction = '';
		public $revision = '';
		public $pipeline;
		public $encoding;
		public $ldpath;
		public $options;

		public $text = '';

		// construct object and collect information about the pair
		function Apertium($_pair, $_direction, $_encoding) {
			print $encoding;
			$this->pair = $_pair;
			$this->direction = $_direction;
			$this->encoding = $_encoding;
			$this->srcroot = '/home/kevin/revdata/downloads/translation/apertium/trunk/apertium-' . $this->pair . '/';
      //$this->srcroot = '/home/kevin/local/share/apertium/apertium-' . $this->pair . '/';
      // the above line, pointing to the installed apertium, does not work because (I think) that install does not contain
      // a modes.xml file, which is called by the readmodes() function below
			$this->instroot = '/home/kevin/local/';
			$this->ldpath = 'LD_LIBRARY_PATH=/home/kevin/local/lib ';

			$this->bindir = $this->instroot . 'bin/';

			if($this->options["display"] == true) {
				print '<div style="float:right; text-align:right; border: white 0px solid;">';
				print '<a href="http://apertium.svn.sourceforge.net/viewvc/apertium/apertium-' . $this->pair . '/">View SVN</a><br/>';
				$revision = shell_exec('svn info ' . $this->srcroot . ' | grep Revision:');
				print $revision;
				print '</div>';
			  echo 'Apertium (' . $this->pair . ', ' . $this->direction . ', ' . $this->srcroot . ', ' . $this->revision . ')' . '<br/>';
			}

			$this->readmodes();
			print '<p></p>';

			print '<pre>';
			print_r($this->pipeline);
			print '</pre>';

		}

		// FT read the modes file into the pipeline array.
    // this (modes.xml) appears to give the files used by the various apertium programs
    // hmm - this function is a good example of why config data should not be held in xml files :-)
		function readmodes() {
			$modesfile = $this->srcroot . 'modes.xml';

			$retval = file_get_contents($modesfile);

			$doc = new DOMDocument;
			$doc->loadXML($retval);

			$modelist = $doc->getElementsByTagName('mode');

			foreach($modelist as $listitem) {
				if($listitem->getAttribute('name') == $this->direction) {
					$pipeindex = 0;
					$pipelist = $listitem->getElementsByTagName('program');
					foreach($pipelist as $program) {
						// FT we need to split out in case there are args in the progname
						$split = explode(' ', $program->getAttribute('name'));
						$this->pipeline[$pipeindex][$split[0]] = '';
						for($i = 1; $i < sizeof($split); $i++) {
							$this->pipeline[$pipeindex][$split[0]] .= $split[$i] . ' ';
						}

						$argv = $program->getElementsByTagName('file');echo 

						foreach($argv as $arg) {
							$this->pipeline[$pipeindex][$split[0]] .= $this->srcroot . $arg->getAttribute('name') . ' ';
						}
						$pipeindex++;
					}
				}
			}
		}

		function destxt($_text, $args) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-destxt';
			return shell_exec($command);
		}

		function deshtml($_text, $args) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . "apertium-deshtml";
			return shell_exec($command);
		}

		function retxt($_text) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-retxt';
			return shell_exec($command);
		}

		function rehtml($_text) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-rehtml';
			return shell_exec($command);
		}


		function ltproc($_text, $args) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'lt-proc ' . $args;
			return shell_exec($command);
		}


		function cgproc($_text, $args) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 /home/fran/local/bin/cg-proc ' . $args;
			return shell_exec($command);
		}

		function pretransfer($_text) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-pretransfer' . $args;
			return shell_exec($command);
		}

		function tagger($_text, $args) {
			$text = str_replace('$?', '$', $_text); // this is stupid
			$text = str_replace('!', '.', $_text); // this is stupid
			$command = 'echo "' . $text . '" | LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-tagger ' . $args;
			return shell_exec($command);
		}

		function transfer($_text, $args) {
			$text = $_text;
			$command = 'echo "' . trim($text) . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-transfer ' . $args;
			return shell_exec($command);
		}

		function interchunk($_text, $args) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 '. $this->bindir . 'apertium-interchunk ' . $args;
			return shell_exec($command);
		}

		function postchunk($_text, $args) {
			$text = $_text;
			$command = 'echo "' . $text . '" | ' . $this->ldpath . ' LANG=en_GB.UTF-8 ' . $this->bindir . 'apertium-postchunk ' . $args;
			return shell_exec($command);
		}

		function exec_stage($index, $command, $args, $_text, $_marcar) {
//			print $index . ' ' . $command . ' ' . $args . '<br/>';
			if($command == 'lt-proc') {
				if(strstr($args, '$1')) {
					if($_marcar) {
						$args = str_replace('$1', '-d', $args);
					} else { 
						$args = str_replace('$1', '-n', $args);
					}
				}
				return $this->ltproc($_text, $args); 
			} else if($command == 'apertium-tagger') {
				return $this->tagger($_text, $args);
			} else if($command == 'apertium-pretransfer') {
				return $this->pretransfer($_text, $args);
			} else if($command == 'apertium-transfer') {
				return $this->transfer($_text, $args);
			} else if($command == 'apertium-interchunk') {
				return $this->interchunk($_text, $args);
			} else if($command == 'apertium-postchunk') {
				return $this->postchunk($_text, $args);
			} else if($command == 'cg-proc') {
				return $this->cgproc($_text, $args);
			} else if($command == 'apertium-destxt') {
				return $this->destxt($_text, $args);
			} else {
				print '<p>Not implemented</p>';
			}
		}

		function normalise($_text) {
			$normal = str_replace('<', '&lt;', $_text);	
			$normal = str_replace('>', '&gt;', $normal);	

			return $normal;
		}

		// do syntax highlighting of the text to make it easier to read
		function highlight($_text) {
			$underlining = false;
			$highlighted = '<code>';
			for($i = 0; $i < strlen($_text); $i++) {
				if($_text[$i] == '<') {
					$highlighted = $highlighted . '<span style="color: #aaaaaa">&lt;';
				} else if($_text[$i] == '>') {
					$highlighted = $highlighted .  '&gt;</span>';
				} else if($_text[$i] == '[') {
					$highlighted = $highlighted . '<span style="color: #aaaaff">[';
				} else if($_text[$i] == ']') {
					$highlighted = $highlighted .  ']</span>';
				} else if($_text[$i] == '{') {
					$highlighted = $highlighted . '<span style="color: #999900">{</span>';
				} else if($_text[$i] == '}') {
					$highlighted = $highlighted .  '<span style="color: #999900">}</span>';
				} else if($_text[$i] == '^') {
					$highlighted = $highlighted .  '<span style="color: #009900">^</span>';
				} else if($_text[$i] == '$') {
					$highlighted = $highlighted .  '<span style="color: #009900">$</span>';
				} else if($_text[$i] == '@') {
					$highlighted = $highlighted .  '<span style="color: #990000">@</span>';
				} else if($_text[$i] == '#') {
					$highlighted = $highlighted .  '<span style="color: #990000">#</span>';
				} else if($_text[$i] == '*') {
					$highlighted = $highlighted .  '<span style="color: #990000">*</span>';
				} else {
					$highlighted = $highlighted . $_text[$i];
				}
/*
				if($_text[$i] == '*') {
					$underlining = true; // set underlining state
					$highlighted = $highlighted . '<u>';
				}
				if($_text[$i+1] == ' ' && $underlining = true) {
					$highlighted = $highlighted . '</u>';
					$underlining = false; // unset underlining state
				}
*/
			}
			$highlighted = $highlighted . '</code>';
			return $highlighted;
		}

		function pipeline() {
			return $this->pipeline;
		}

		function translate($_text, $_options, $_format) {
			return $this->translate_with_stage($_text, $_options, $_format, -1);
		}

		function translate_with_stage($_text, $_options, $_format, $_endstage) {
			$this->options = $_options;
      
/*
			if($format == 'html') {
				$this->text = $this->dehtml($_text, '');
			} else {
				$this->text = $this->destxt($_text, '');
			}
*/			

      print '<p></p>';
			$this->text = $_text;

			$tmp = str_replace('`', '\'', $this->text); // FT sassy crackers tryin to read my files
			$tmp = str_replace('\\', '', $tmp);

			$tmp = str_replace('ş', 'ș', $tmp);
			$tmp = str_replace('ţ', 'ț', $tmp);

			if($this->options["display"] == "true") {
				print '<p style="align: justify;">' . $tmp . '</p>';
			}

			if($_endstage == -1) {
				$endstage = sizeof($this->pipeline);
			} else {
				$endstage = $_endstage;
			}

			for($i = 0; $i < $endstage; $i++) {
				foreach($this->pipeline[$i] as $key => $val) {
					$tmp = $this->exec_stage($i, $key, $val, $tmp, $this->options["marcar"]);
					$tmp = str_replace('\\', '', $tmp);
					if($this->options["interm"]) {
						print '<div style="float: right; border: 0px"><code><u>' . $key . '</u></code></div>';
						print '<p style="align: justify;">' . $this->highlight($tmp) . '</p>';
					}
				}
			}

			if($format == 'html') {
				$tmp = $this->rehtml($tmp);
			} else {
				$tmp = $this->retxt($tmp);
			}

			if($this->options["display"]) {
				print $tmp;
//				print $this->highlight($tmp) . '<p/>';
			} else {
				return $tmp;
			}
		}
	}
?>
