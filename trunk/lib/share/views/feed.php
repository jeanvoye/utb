<?php
class shareFeedView extends ezcMvcView// implements ezcMvcFeedDecorator
{
	public $feedType;
	public $id;
	public $link;
	public $title;

	function __construct( ezcMvcRequest $req, ezcMvcResult $res, $feedType = 'rss2' )
	{
		parent::__construct( $req, $res );
		$this->feedType = $feedType;

	}

	function decorateFeed( ezcFeed $feed )
	{
		$feed->id = $this->id;
		$feed->title = $this->title;
		$feed->link = $this->id;
		$feed->description = 'This feed lists all the latest updates from TheWire.';
		$feed->generator = 'The Wire';
		$feed->updated = time();
		$feed->author = 'thewire';
	}

	function getItemVariable()
	{
		return 'items';
	}

	function decorateFeedItem( ezcFeedEntryElement $item, $data )
	{
		$installDomain = $GLOBALS['ini']->getSetting( 'TheWire', 'installDomain' );

		$author = $item->add( 'author' );
		$author->name = $data['fullname'];
		$author->email = "{$data['user_id']}@" . $GLOBALS['ini']->getSetting( 'TheWire', 'mailDomain' );

		$link = $item->add( 'link' );
		$link->href= "http://$installDomain/update/{$data['id']}";

		$item->title = $data['user_id'] . ': '. $data['status'];
		$item->description = $data['fullname'] . ': '. $data['status'];
		$item->published = $data['date'];
		$item->id= "http://$installDomain/update/{$data['id']}";
		$item->updated = $data['date'];
	}

	function createZones( $layout )
	{
		$zone = new ezcMvcFeedViewHandler( 'page_layout', $this, $this->feedType );
		return array( $zone );
	}
}
?>
