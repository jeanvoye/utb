<?php
class shareTemplateFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition( $name )
    {
        switch ( $name )
        {
            case "fetchTimeZones":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = "shareTemplateFunctions";
                $def->method = "fetchTimeZones";
                return $def;

            case "currentUser":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = "shareTemplateFunctions";
                $def->method = "currentUser";
                return $def;
        }

        return false;
    }

	public static function fetchTimeZones()
	{
		return timezone_identifiers_list();
	}

	public static function currentUser()
	{
		return isset( $_SESSION['ezcAuth_id'] ) ? $_SESSION['ezcAuth_id'] : '';
	}
}
