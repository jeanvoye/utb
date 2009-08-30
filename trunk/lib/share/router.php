<?php
class shareRouter extends ezcMvcRouter
{
	public function createRoutes()
	{
		return array(
			new ezcMvcRailsRoute( '/', 'shareHomeController', 'list' ),
			new ezcMvcRailsRoute( '/updates', 'shareHomeController', 'list' ),
			new ezcMvcRailsRoute( '/updates.js', 'shareHomeController', 'list' ),
			new ezcMvcRailsRoute( '/updates.atom', 'shareHomeController', 'list' ),
			new ezcMvcRailsRoute( '/updates/:offset', 'shareHomeController', 'list' ),
			new ezcMvcRailsRoute( '/update/:id', 'shareHomeController', 'show' ),
			new ezcMvcRailsRoute( '/update/:id/delete', 'shareHomeController', 'delete' ),
			new ezcMvcRailsRoute( '/tag', 'shareTagController', 'list' ),
			new ezcMvcRailsRoute( '/tag/:tagName', 'shareTagController', 'display' ),
			'personIndex' => new ezcMvcRailsRoute( '/person', 'sharePersonController', 'list' ),
			new ezcMvcRailsRoute( '/person/all', 'sharePersonController', 'status' ),
			new ezcMvcRailsRoute( '/person/:personName', 'sharePersonController', 'display' ),
			new ezcMvcRailsRoute( '/latest-links', 'shareLinkController', 'list' ),
			new ezcMvcRailsRoute( '/latest-links/:offset', 'shareLinkController', 'list' ),
			new ezcMvcRailsRoute( '/link/last', 'shareLinkController', 'redirectToLast' ),
			'linkDisplay' => new ezcMvcRailsRoute( '/link/:linkId', 'shareLinkController', 'display' ),
			new ezcMvcRailsRoute( '/link/:linkId/redirect', 'shareLinkController', 'redirect' ),
			new ezcMvcRailsRoute( '/help', 'shareHelpController', 'help' ),
			new ezcMvcRailsRoute( '/prefs', 'sharePreferencesController', 'display' ),
			new ezcMvcRailsRoute( '/prefs/update', 'sharePreferencesController', 'update' ),
			new ezcMvcRailsRoute( '/register/submit', 'shareAuthController', 'register-submit' ),
			new ezcMvcRailsRoute( '/register', 'shareAuthController', 'register' ),
			new ezcMvcRailsRoute( '/basic-auth-required', 'shareAuthController', 'basic-auth-required' ),
			new ezcMvcRailsRoute( '/login-required', 'shareAuthController', 'login-required' ),
			new ezcMvcRailsRoute( '/login', 'shareAuthController', 'login' ),
			new ezcMvcRailsRoute( '/logout', 'shareAuthController', 'logout' ),
			new ezcMvcRailsRoute( '/fatal', 'shareFatalController', 'show' ),
			new ezcMvcRailsRoute( '/avatar/update', 'shareAvatarController', 'update' ),
			new ezcMvcRailsRoute( '/avatar/:personName', 'shareAvatarController', 'display' ),
			new ezcMvcRailsRoute( '/user_graph', 'shareGraphController', 'user' ),
			new ezcMvcRailsRoute( '/source_graph', 'shareGraphController', 'source' ),
			new ezcMvcRailsRoute( '/add', 'shareAddController', 'add' ),
			new ezcMvcRailsRoute( '/rss', 'shareRssController', 'updates' ),
			new ezcMvcRailsRoute( '/rss/tag/:tagName', 'shareRssController', 'tag' ),
            new ezcMvcRailsRoute( '/debug/:type', 'shareDebugController', 'debug' ),
		);
	}
}
?>
