<?php
class shareHomeController extends ezcMvcController
{
	public function doDelete()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( '*' )
		  ->from( 'message' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->where( $q->expr->eq( 'message.id', $this->id ) )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		if ( $_SESSION['ezcAuth_id'] == $r[0]['user_id'] )
		{
			$q = ezcDbInstance::get()->createDeleteQuery();
			$q->deleteFrom( 'message' )
			  ->where( $q->expr->eq( 'id', $q->bindValue( $this->id ) ) );
			$s = $q->prepare();
			$s->execute();

			$q = ezcDbInstance::get()->createDeleteQuery();
			$q->deleteFrom( 'message_tag' )
			  ->where( $q->expr->eq( 'message_id', $q->bindValue( $this->id ) ) );
			$s = $q->prepare();
			$s->execute();
			die( "OK" );
		}
		die( "FAIL" );
	}

	public function doList()
	{
		$offset = isset( $this->offset ) ? (int) $this->offset : 0;
		$limit = 20;

		if ( $offset != 0 )
		{
			$refresh = false;
		}

		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'message.*, user.fullname' )
		  ->from( 'message' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( $limit, $offset );
		$s = $q->prepare();
		$s->execute();

		$messages = shareFormat::formatMessages( $s->fetchAll() );

		$res = new ezcMvcResult();
		$res->variables['updates'] = $messages;
		$res->variables['next'] = $offset + $limit;
		$res->variables['limit'] = $limit;
		$res->variables['refresh'] = true;

		$res->variables['rss'] = '';

		return $res;
	}

	public function doShow()
	{
		$id = (int) $this->id;
		$refresh = false;

		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'message.*, user.fullname' )
		  ->from( 'message' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->where( $q->expr->eq( 'message.id', $q->bindValue( $id ) ) )
		  ->orderBy( 'date', ezcQuerySelect::DESC );
		$s = $q->prepare();
		$s->execute();

		$messages = shareFormat::formatMessages( $s->fetchAll() );

		$res = new ezcMvcResult();
		$res->variables['updates'] = $messages;
		$res->variables['id'] = $id;

		$res->variables['rss'] = '';

		return $res;
	}
}
?>
