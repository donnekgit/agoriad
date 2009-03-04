// This script is based on the sample code provided in Kevin Miller's Accordion v2.0
// (http://stickmanlabs.com/accordion).

// This loads the accordions for the various stages of Apertium processing.
// The Accordions script only allows one accordion to be open at the same time, 
// so to get around this we set up multiple horizontal_containers.
// Each process gets its own container - you can group several stages within one
// container, but that means that you cannot compare two stages side by side
// if they are in the same container - opening one closes the other.

// Changing the name "horizontal_container" will require multiple sections in the
// CSS file, so I don't do that.

Event.observe(window, 'load', loadAccordions, false);

// Width of the space given to the open accordion.
// Anything less than 300 doesn't make much sense, since the full POS table can't be seen.
var mywidth = '300';

function loadAccordions() {

  var ltproc1Accordion = new accordion('horizontal_container1', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var cgprocAccordion = new accordion('horizontal_container2', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var taggerAccordion = new accordion('horizontal_container3', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var pretransferAccordion = new accordion('horizontal_container4', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var transferAccordion = new accordion('horizontal_container5', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var interchunkAccordion = new accordion('horizontal_container6', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var postchunkAccordion = new accordion('horizontal_container7', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  var ltproc2Accordion = new accordion('horizontal_container8', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  
  var ltproc3Accordion = new accordion('horizontal_container9', {
    classNames : {
      toggle : 'horizontal_accordion_toggle',
      toggleActive : 'horizontal_accordion_toggle_active',
      content : 'horizontal_accordion_content'
    },
    defaultSize : {
      width : mywidth
    },
    direction : 'horizontal'
  });
  
  // You can set up different stages to load in an open accordion by default.
  // Uncomment the ones you want.  In anything up to a 1200-pixel display,
  // 2 is the sensible maximum, because after that the display gets messed up 
  // as accordions fall off the end.  In a 1600-pixel display, 3 accordions can be open.
  
  //ltproc1Accordion.activate($$('#horizontal_container1 .horizontal_accordion_toggle') [0]);
  //cgprocAccordion.activate($$('#horizontal_container2 .horizontal_accordion_toggle') [0]);
  //taggerAccordion.activate($$('#horizontal_container3 .horizontal_accordion_toggle') [0]);
  //pretransferAccordion.activate($$('#horizontal_container4 .horizontal_accordion_toggle') [0]);
  transferAccordion.activate($$('#horizontal_container5 .horizontal_accordion_toggle') [0]);
  //interchunkAccordion.activate($$('#horizontal_container6 .horizontal_accordion_toggle') [0]);
  //postchunkAccordion.activate($$('#horizontal_container7 .horizontal_accordion_toggle') [0]);
  //ltproc2Accordion.activate($$('#horizontal_container8 .horizontal_accordion_toggle') [0]);
  ltproc3Accordion.activate($$('#horizontal_container9 .horizontal_accordion_toggle') [0]);
  
// If you decide to use groups of stages in an accordion instead of one stage to an 
// accordion, the integer in square brackets sets the default open one, where [0] is the first.

}