<?php
class shareMiniView extends ezcMvcView
{
	public $contentTemplate;

	function createZones( $layout )
	{
		$zones = array();
		$zones[] = new ezcMvcTemplateViewHandler( 'add_box', 'box-add-update-mini.ezt' );
		$zones[] = new ezcMvcTemplateViewHandler( 'content', $this->contentTemplate );
		$zones[] = new ezcMvcTemplateViewHandler( 'page_layout', 'main-mini.ezt' );
		return $zones;
	}
}
?>
