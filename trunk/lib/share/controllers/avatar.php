<?php
class shareAvatarController extends ezcMvcController
{
	public function doDisplay()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'avatar' )->from( 'avatar' )->where( $q->expr->eq( 'user_id', $q->bindValue( $this->personName ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		if ( count( $r ) === 0 )
		{
			$q = ezcDbInstance::get()->createSelectQuery();
			$q->select( 'avatar' )->from( 'avatar' )->where( $q->expr->eq( 'user_id', $q->bindValue( 'default' ) ) );
			$s = $q->prepare();
			$s->execute();
			$r = $s->fetchAll();
		}

		$res = new ezcMvcResult();
		$res->variables['graphic'] = base64_decode( $r[0]['avatar'] );
		$res->content = new ezcMvcResultContent;
		$res->content->type = 'image/png';
		$res->cache = new ezcMvcResultCache;
		$res->cache->expire = new DateTime;
		$res->cache->controls = array( 'max-age=3600', 'must-revalidate' );
		$res->cache->pragma = 'public';

		return $res;
	}

	public function doUpdate()
	{
		shareApp::updateAvatar();
		$res = new ezcMvcResult;
		$res->status = new ezcMvcExternalRedirect( '/prefs' );
		return $res;
	}
}
?>
