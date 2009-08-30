<?php
class shareView extends ezcMvcView
{
	public $contentTemplate;

	function createZones( $layout )
	{
		$zones = array();
		$zones[] = new ezcMvcTemplateViewHandler( 'add_box', 'box-add-update.ezt' );
		$zones[] = new ezcMvcTemplateViewHandler( 'zoneTagCloud', 'tagcloud.ezt' );
		$zones[] = new ezcMvcTemplateViewHandler( 'zonePeopleCloud', 'peoplecloud.ezt' );
		$zones[] = new ezcMvcTemplateViewHandler( 'content', $this->contentTemplate );
		$zones[] = new ezcMvcTemplateViewHandler( 'page_layout', 'main.ezt' );
		return $zones;
	}
}
?>
