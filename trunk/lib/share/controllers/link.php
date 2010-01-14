<?php
class shareLinkController extends ezcMvcController
{
	public function doList()
	{
		$offset = isset( $this->offset ) ? (int) $this->offset : 0;
		$limit = 20;

		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'message.*, user.fullname', 'links.link' )
		  ->from( 'links' )
		  ->innerJoin( 'message', 'links.message_id', 'message.id' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( $limit, $offset );
		$s = $q->prepare();
		$s->execute();

		$messages = shareFormat::formatMessages( $s->fetchAll() );

		$res = new ezcMvcResult;
		$res->variables['updates'] = $messages;
		$res->variables['next'] = $offset + $limit;
		$res->variables['limit'] = $limit;
		return $res;
	}

	public function fetchLink( $linkId )
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$linkId = base_convert( $this->linkId, 36, 10 );
		$q->select( 'link', 'message_id' )->from( 'links' )->where( $q->expr->eq( 'id', $q->bindValue( $linkId ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		return count( $r ) ? $r[0] : null;
	}

	public function doDisplay()
	{
		$r = $this->fetchLink( $this->linkId );
		if ( isset( $r['link'] ) )
		{
			if ( $r['message_id'] )
			{
				$q = ezcDbInstance::get()->createSelectQuery();
				$q->select( '*' )
				  ->from( 'message' )
				  ->innerJoin( 'user', 'message.user_id', 'user.id' )
				  ->where( $q->expr->eq( 'message.id', $q->bindValue( $r['message_id'] ) ) );
				$s = $q->prepare();
				$s->execute();
				$t = $s->fetchAll();
				$messages = shareFormat::formatMessages( $t );
			}
			else
			{
				$messages = '';
			}

			$res = new ezcMvcResult;
			$res->variables['shortLink'] = $this->linkId;
			$res->variables['url'] = $r['link'];
			$res->variables['updates'] = $messages;
			return $res;
		}
		throw new Exception( "No such link: {$this->linkId}" );
	}

	public function doRedirect()
	{
		$r = $this->fetchLink( $this->linkId );
		if ( isset( $r['link'] ) )
		{
			$res = new ezcMvcResult;
			$res->status = new ezcMvcExternalRedirect( $r['link'] );
			return $res;
		}
	}

	public function doRedirectToLast()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( $q->expr->max( 'id' ) )->from( 'links' );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();
		$linkId = $r[0][0];
		$linkId = base_convert( $linkId, 10, 36 );

		$request = clone $this->request;
		$request->uri = $this->router->generateUrl( 'linkDisplay', array( 'linkId' => $linkId ) );
		return new ezcMvcInternalRedirect( $request );
	}
}
?>
