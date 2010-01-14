<?php
class shareRssController extends ezcMvcController
{
	public function doUpdates()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'message.*, user.fullname' )
		  ->from( 'message' )
		  ->leftJoin( 'user', 'message.user_id', 'user.id' )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();

		$messages = shareFormat::substituteLinksInMessages( $s->fetchAll() );

		$res = new ezcMvcResult();
		$res->variables['items'] = $messages;
		$res->content = new ezcMvcResultContent;
		$res->content->type = 'application/rss+xml';
		return $res;
	}

	public function doTag()
	{
		$tag = preg_replace( '@[^a-z]@i', '', $this->tagName );

		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'message.*, user.fullname' )
		  ->from( 'message' )
		  ->leftJoin( 'user', 'message.user_id', 'user.id' )
		  ->innerJoin( 'message_tag', 'message.id', 'message_tag.message_id' )
		  ->where( $q->expr->eq( 'tag', $q->bindValue( $tag ) ) )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();

		$res = new ezcMvcResult();
		$res->variables['items'] = $s->fetchAll();
		$res->content = new ezcMvcResultContent;
		$res->content->type = 'application/rss+xml';
		return $res;
	}
}
?>
