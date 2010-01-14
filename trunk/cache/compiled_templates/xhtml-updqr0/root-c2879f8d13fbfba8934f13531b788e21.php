<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->updates))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'updates',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->next))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'next',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->limit))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'limit',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<h1>Latest Updates</h1>\n<div class='navigation'><a class='navigation' href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/updates/";
$i_output .= htmlspecialchars($this->send->next);
$i_output .= "'>Next ";
$i_output .= htmlspecialchars($this->send->limit);
$i_output .= " updates</a></div>\n<div class='last-updated'>Last updated: ";
$i_output .= htmlspecialchars((date("Y-m-d H:i:s")));
$i_output .= "</div>\n\n";
$_t = clone $this->template;
$_t->send = new ezcTemplateVariableCollection();
$_t->send->msgList = $this->send->updates;
$_t->send->installDomain = $this->send->installDomain;
$i_output .= $_t->process("format-msgs.ezt",$this->template->usedConfiguration);
unset($_t);
$i_output .= "\n<div class='navigation'><a class='navigation' href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/updates/";
$i_output .= htmlspecialchars($this->send->next);
$i_output .= "'>Next ";
$i_output .= htmlspecialchars($this->send->limit);
$i_output .= " updates</a></div>\n";
return $i_output;
?>
