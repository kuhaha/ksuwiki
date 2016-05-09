(function(){
  // For ksuwiki plugin 
  var prefix = "webroot/assets/snippet/";        
  // For local test 
  //var prefix = "";

  var load = function (type, file) {
    if (type=='js'){
      var script = prefix + "js/" + file;
	    document.write('<script type="text/javascript" src="'+ script +'"></script>');
    }else if (type=='css'){
      var css =  prefix + "css/" + file;
	    document.write('<link rel="stylesheet" href="' + css + '">');
    }else{
      document.write('<script type="text/javascript" src="'+ file +'"></script>');
    }
  }
  var jquery = [
    "http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"
  , "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"
  ];
   for(var i=0; i<jquery.length; i++){
     load('ext', jquery[i]);
   }
  load('css',"snippet.css");
  load('js', "ace/ace.js"); 
  load('js', "acorn/acorn.js");     // javascript preprocessor
  load('js', "acorn/util/walk.js"); // dom walk through
  load('js', "sandbox.js");
  load('js', "snippet.js");
})();
