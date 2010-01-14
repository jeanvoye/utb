<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->currentZone))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'currentZone',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<select name=\"timezone\">\n";
foreach (shareTemplateFunctions::fetchTimeZones() as $t_zone)
{
    $i_output .= "<option value=\"";
    $i_output .= htmlspecialchars($t_zone);
    $i_output .= "\"";
    if ($t_zone == $this->send->currentZone)
    {
        $i_output .= "} selected=\"yes\"";
    }
    $i_output .= ">";
    $i_output .= htmlspecialchars($t_zone);
    $i_output .= "</option>\n";
}
$i_output .= "</select>\n";
return $i_output;
?>
