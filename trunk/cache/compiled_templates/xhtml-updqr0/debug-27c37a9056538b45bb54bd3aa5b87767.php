<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->type))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'type',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->debugMessage))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'debugMessage',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<h1>Debug</h1>\ntype : ";
$i_output .= htmlspecialchars($this->send->type);
$i_output .= "<br />\nmessage : ";
$i_output .= htmlspecialchars((ezcTemplateDebug::debug_dump($this->send->debugMessage)));
$i_output .= "\n\n";
$i_output .= "\n";
$i_output .= "\n";
return $i_output;
?>
