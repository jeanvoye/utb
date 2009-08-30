<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->currentUrl))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'currentUrl',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<div class=\"update-box\">\n\t<form name='add' action='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/add' method='post'>\n\t\t<div>What are you working on:<br/>\n\t\t<input name=\"update\" style=\"width: 95%\" /><br/>\n\t\t<input name=\"redirUrl\" type=\"hidden\" value='";
$i_output .= htmlspecialchars($this->send->currentUrl);
$i_output .= "'/>\n\t\t<br/>\n\t\t<input type=\"submit\" name='add-update' value='Go'/> <span class=\"tiny\">(Do not forget to tag your message)</span>\n\t\t</div>\n\t</form>\n</div>\n";
return $i_output;
?>
