<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->mailDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'mailDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->defaultTimezone))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'defaultTimezone',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->jabberDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'jabberDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<h1>Register</h1>\n\n<div class=\"pres\">\n\n<form class=\"prefs\" action='register/submit' method='post'>\nUser name: <input name='user_id' size='4'/>@";
$i_output .= htmlspecialchars($this->send->mailDomain);
$i_output .= " (This is where the password will be sent to)<br/><br/>\nFull name: <input name='fullname' size='32'/> (Like \"Derick Rethans\")<br/><br/>\nJabber Account: <input name=\"jabber_name\"/> (Like \"dr@";
$i_output .= htmlspecialchars($this->send->jabberDomain);
$i_output .= "\")<br/><br/>\nTimezone: ";
$_t = clone $this->template;
$_t->send = new ezcTemplateVariableCollection();
$_t->send->currentZone = $this->send->defaultTimezone;
$i_output .= $_t->process("timezones.ezt",$this->template->usedConfiguration);
unset($_t);
$i_output .= "<br/><br/>\n<br/>\n<input type='submit' name='reg' value=\"Register\"/>\n</form>\n</div>\n";
return $i_output;
?>
