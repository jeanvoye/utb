<?php
class shareAuthController extends ezcMvcController
{
	public function doBasicAuthRequired()
	{
		$res = new ezcMvcResult;
		$res->status = new ezcMvcResultUnauthorized( 'TheWire' );
		return $res;
	}

	public function doRegister()
	{
		$res = new ezcMvcResult;
		$res->variables['defaultTimezone'] = $GLOBALS['ini']->getSetting( 'TheWire', 'defaultTimezone' );
		return $res;
	}

	public function doRegisterSubmit()
	{
		$res = new ezcMvcResult;
		if ( isset( $this->reg ) )
		{
			$res->variables['success'] = shareApp::register( $message );
			$res->variables['message'] = $message;
		}
		return $res;
	}

	public function doLoginRequired()
	{
		$res = new ezcMvcResult;
		$res->variables['redirUrl'] = $this->redirUrl;

		$err = array(
			'ezcAuthenticationDatabaseFilter' => array(
				ezcAuthenticationHtpasswdFilter::STATUS_USERNAME_INCORRECT => 'Incorrect or no credentials provided.',
				ezcAuthenticationHtpasswdFilter::STATUS_PASSWORD_INCORRECT => 'Incorrect or no credentials provided.'
			),
			'ezcAuthenticationSession' => array(
				ezcAuthenticationSession::STATUS_EMPTY => 'No session',
				ezcAuthenticationSession::STATUS_EXPIRED => 'Session expired'
			),
			'logout' => array(
				1 => 'You logged out',
			)
		);

		$reasonText = array();

		$reasons = $this->reasons;
		foreach ( $reasons as $line )
		{
			list( $key, $value ) = each( $line );
			$reasonText[] = $err[$key][$value];
		}
		$res->variables['reasons']  = $reasonText;
		$res->variables['redirUrl'] = $this->redirUrl;
		return $res;
	}

	public function doLogin()
	{
		// obtain credentials from POST
		$user = isset( $_POST['user'] ) ? $_POST['user'] : null;
		$password = isset( $_POST['password'] ) ? $_POST['password'] : null;
		$redirUrl = isset( $_POST['redirUrl'] ) ? $_POST['redirUrl'] : '/';

		$database = new ezcAuthenticationDatabaseInfo( ezcDbInstance::get(), 'user', array( 'id', 'password' ) );
		$databaseFilter = new ezcAuthenticationDatabaseFilter( $database ); 

		$options = new ezcAuthenticationSessionOptions();
		$options->validity = 86400;

		$session = new ezcAuthenticationSession( $options );
		$session->start();

		// use the options object when creating a new Session object
		$credentials = new ezcAuthenticationPasswordCredentials( $user, md5( $password ) );
		$authentication = new ezcAuthentication( $credentials );
		$authentication->session = $session; 
		$authentication->addFilter( $databaseFilter );

		if ( !$authentication->run() )
		{
			$request = clone $this->request;
			$status = $authentication->getStatus();
			$request->variables['redirUrl'] = $redirUrl;
			$request->variables['reasons']  = $status;

			$request->uri = '/login-required';
			return new ezcMvcInternalRedirect( $request );
		}

		$res = new ezcMvcResult;
		$res->status = new ezcMvcExternalRedirect( $redirUrl );
		return $res;
	}

	public function doLogout()
	{
		$options = new ezcAuthenticationSessionOptions();
		$options->validity = 86400;

		$session = new ezcAuthenticationSession( $options );
		$session->start();
		$session->destroy();

		$res = new ezcMvcResult;
		$res->status = new ezcMvcExternalRedirect( '/' );
		return $res;
	}
}
?>
