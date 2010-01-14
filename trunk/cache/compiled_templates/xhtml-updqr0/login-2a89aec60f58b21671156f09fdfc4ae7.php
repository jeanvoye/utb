<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->redirUrl))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'redirUrl',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->reasons))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'reasons',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->mailDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'mailDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<h1>Login</h1>\n\n<div class=\"login\">\n<p>\nYou are not logged in, because:\n</p>\n<ul>\n";
foreach ($this->send->reasons as $t_reason)
{
    $i_output .= "<li>";
    $i_output .= htmlspecialchars($t_reason);
    $i_output .= "</li>\n";
}
$i_output .= "</ul>\n\n<form class=\"login\" action='/login' method='post'>\n<div>\nEmail: <input name='user' size='4'/>@";
$i_output .= htmlspecialchars($this->send->mailDomain);
$i_output .= "<br/>\nPassword: <input type='password' name='password'/><br/>\n<br/>\n<input type='hidden' name='redirUrl' value='";
$i_output .= htmlspecialchars($this->send->redirUrl);
$i_output .= "'/>\n<input type='submit' value=\"Login\"/>\n</div>\n</form>\n</div>\n";
return $i_output;
?>
