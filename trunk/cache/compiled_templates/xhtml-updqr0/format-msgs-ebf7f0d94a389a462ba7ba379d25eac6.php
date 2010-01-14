<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->msgList))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'msgList',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->showLink))
{
    $this->send->showLink = false;
}
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
foreach ($this->send->msgList as $t_update)
{
    $i_output .= "\n<div class=\"status\">\n\t<table><tr>\n\t<td valign='top'>\n\t\t<img class='avatar' src='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/avatar/";
    $i_output .= htmlspecialchars($t_update["user_id"]);
    $i_output .= "'/>\n\t</td>\n\t<td style='float: right; width: 100%'>\n\t\t<span class='user'><a class='person' href='http://";
    $i_output .= htmlspecialchars($this->send->installDomain);
    $i_output .= "/person/";
    $i_output .= htmlspecialchars($t_update["user_id"]);
    $i_output .= "'>";
    $i_output .= htmlspecialchars($t_update["fullname"]);
    $i_output .= "</a>:</span>\n\t\t<span class='message'>";
    $i_output .= $t_update["contents"];
    $i_output .= "</span>\n\t\t<span class='date'>";
    $i_output .= " from ";
    $i_output .= "</span>\n";
    if ($t_update["user_id"] == shareTemplateFunctions::currentUser())
    {
        $i_output .= "\t<span class='delete'><a href='javascript:deleteUpdate(";
        $i_output .= htmlspecialchars($t_update["id"]);
        $i_output .= ");'>[ X ]</a></span>\n";
    }
    if ($this->send->showLink)
    {
        $i_output .= "\t<br/><br/>\n\t<span class='link'>This link expands to:<br/> <a href='";
        $i_output .= htmlspecialchars($t_update["link"]);
        $i_output .= "'>";
        $i_output .= htmlspecialchars($t_update["link"]);
        $i_output .= "</a></span>\n";
    }
    $i_output .= "\t</td>\n\t</tr></table>\n</div>\n";
}
return $i_output;
?>
