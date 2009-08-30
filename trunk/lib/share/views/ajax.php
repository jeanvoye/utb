<?php
class shareAjaxView extends ezcMvcView
{
	public $contentTemplate;

	function createZones( $layout )
	{
		$zones = array();
		$zones[] = new ezcMvcTemplateViewHandler( 'content', $this->contentTemplate );
		$zones[] = new ezcMvcJsonViewHandler( 'page_layout', NULL );
		return $zones;
	}
}
?>
