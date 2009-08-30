<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->tagCloud))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'tagCloud',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$t_tags = $this->send->tagCloud["tags"];
$t_highest = $this->send->tagCloud["highest"];
$i_output .= "<div id=\"tagcloud\">\n";
foreach ($t_tags as $t_tag => $t_count)
{
    $i_output .= "<span class='tagcloud";
    $i_output .= htmlspecialchars((round((($t_count / $t_highest) * 6))));
    $i_output .= "'><a class='tag' href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/tag/";
    $i_output .= htmlspecialchars($t_tag);
    $i_output .= "'>";
    $i_output .= htmlspecialchars($t_tag);
    $i_output .= "</a></span>\n";
}
$i_output .= "</div>\n";
return $i_output;
?>
