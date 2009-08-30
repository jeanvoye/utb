<?php
class shareDebugController extends ezcMvcController
{
	public function doDebug()
	{
        $res = new ezcMvcResult();

        $q = ezcDbInstance::get()->query( "SELECT * FROM user" );
        //var_dump( $q );
        //$s = $q->prepare();
        $q->execute();
        $r = $q->fetchAll();

        $res->variables['debugMessage'] = $r;
        $res->variables['type'] = $this->type;
        return $res;




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
}
?>
