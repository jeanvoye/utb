<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->content))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'content',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->user))
{
    $this->send->user = "Anonymous";
}
if ( !isset($this->send->refresh))
{
    $this->send->refresh = false;
}
if ( !isset($this->send->rss))
{
    $this->send->rss = NULL;
}
if ( !isset($this->send->zoneTagCloud))
{
    $this->send->zoneTagCloud = NULL;
}
if ( !isset($this->send->zonePeopleCloud))
{
    $this->send->zonePeopleCloud = NULL;
}
if ( !isset($this->send->add_box))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'add_box',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->debugOutput))
{
    $this->send->debugOutput = NULL;
}
$i_output .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n<head>\n<title>Un Truc Bien</title>\n<link type=\"text/css\" rel=\"stylesheet\" href=\"http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/stylesheets/core.css\"/>\n";
if ($this->send->debugOutput !== NULL)
{
    $i_output .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/stylesheets/ezc-debug.css\"/>\n";
}
$i_output .= "\n";
if ($this->send->rss !== NULL)
{
    $i_output .= "<link rel=\"alternate\" type=\"application/atom+xml\" href=\"http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/rss";
    $i_output .= htmlspecialchars($this->send->rss);
    $i_output .= "\" title=\"TheWire feed\" />\n";
}
$i_output .= "\n<script src=\"http://yui.yahooapis.com/2.5.2/build/yahoo/yahoo-min.js\"></script>\n<script src=\"http://yui.yahooapis.com/2.5.2/build/event/event-min.js\"></script>\n<script src=\"http://yui.yahooapis.com/2.5.2/build/connection/connection-min.js\"></script>\n<script>\n";
$i_output .= "\nvar handleFailure = function(o)\n{\n}\n\nvar handleDeleteSuccess = function(o)\n{\n";
$i_output .= "\n";
if ($this->send->refresh)
{
    $i_output .= "setTimeout(\"reload();\",500);\n";
}
else
{
    $i_output .= "window.location.reload();\n";
}
$i_output .= "\n}\n\nvar deleteCallback =\n{\n\tsuccess: handleDeleteSuccess,\n\tfailure: handleFailure\n};\n\nfunction deleteUpdate(id)\n{\n\tvar sUrl = \"/update/\" + id + \"/delete\";\n    var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, deleteCallback);\n}\n";
$i_output .= "\n</script>\n";
if ($this->send->refresh)
{
    $i_output .= "<script>\n\n\nvar handleSuccess = function(o){\n    if(o.responseText !== undefined){\n        var main = document.getElementById('main');\n        list = eval( \"(\" + o.responseText + \")\" );\n        main.innerHTML = list.content;\n    }\n}\n\nvar callback =\n{ \n  success:handleSuccess,\n  failure: handleFailure,\n  argument: { foo:\"foo\", bar:\"bar\" }\n};\n\nfunction reload(){\n    var sUrl = \"updates.js\";\n    var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);\n\tsetTimeout(\"reload();\",30000);\n}\n\nsetTimeout(\"reload();\",30000);\n\n</script>\n";
}
$i_output .= "\n<script>\n";
$i_output .= "\nfunction f(){if (document.add) { document.add.update.focus() } }\n";
$i_output .= "\n</script>\n</head>\n<body onLoad='f();'>\n<div id=\"everything\">\n<ul id=\"menu\">\n";
if ($this->send->user != "Anonymous")
{
    $i_output .= "<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/'>Home</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/tag'>Latest Tag Updates</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/person'>Latest Person Updates</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/latest-links'>Latest Link Updates</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/person/all'>Current Status</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/help'>Help</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/logout'>Logout</a></li>\n";
}
else
{
    $i_output .= "<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/'>Login</a></li>\n<li><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/register'>Register</a></li>\n";
}
$i_output .= "</ul>\n<br/>\n<div id=\"main\">\n\t";
$i_output .= $this->send->content;
$i_output .= "\n</div>\n\n<div style=\"float: right\">\n<div id=\"user\">\n\t<div class=\"username\">";
$i_output .= htmlspecialchars($this->send->user);
$i_output .= "</div>\n\t";
if ($this->send->user != "Anonymous")
{
    $i_output .= "<div><a href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/prefs'>Preferences</a></div>\n";
}
$i_output .= "</div>\n\n";
if ($this->send->user != "Anonymous")
{
    $i_output .= "<div id=\"side\">\n";
    $i_output .= $this->send->add_box;
    $i_output .= "\n</div>\n\n";
    $i_output .= $this->send->zoneTagCloud;
    $i_output .= "\n";
    $i_output .= $this->send->zonePeopleCloud;
    $i_output .= "\n\n<div class=\"graph\">\n<object data='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/user_graph' height=\"200\" width=\"300\" type=\"image/svg+xml\">\n<embed src='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/user_graph' height=\"200\" width=\"300\" type=\"image/svg+xml\"/>\n</object>\n</div>\n\n";
    $i_output .= "\n";
}
$i_output .= "</div>\n\n<div class=\"clear\"></div>\n\n";
if ($this->send->debugOutput)
{
    $i_output .= $this->send->debugOutput;
    $i_output .= "\n";
}
$i_output .= "</body>\n</html>\n";
return $i_output;
?>
