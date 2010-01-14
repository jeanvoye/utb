<?php
// Generated PHP file from template code.
// If you modify this file your changes will be lost when it is regenerated.
$this->checkRequirements(1,array("disableCache" => false));
$i_output = "";
if ( !isset($this->send->installDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'installDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->jabberUser))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'jabberUser',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->jabberDomain))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'jabberDomain',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
if ( !isset($this->send->bugLinkFormat))
{
    
    throw new ezcTemplateRuntimeException( sprintf("The external (use) variable '%s' is not set in template: %s and called from %s", 'bugLinkFormat',  $this->template->stream,  ( sizeof($this->template->streamStack) >= 2 ? $this->template->streamStack[sizeof($this->template->streamStack) - 2] : 'the application code') ) );
}
$i_output .= "<h1>Help for TheWire</h1>\n\n<h2>Introduction</h2>\n\n<p>\nTheWire is a tool for microblogging, this allows you to mention what you're\ndoing at that moment.  Great example of that are:\n</p>\n\n<blockquote>\n\"Trying to add icons to a vba treeview control. So far works by adding an .ico\nfile directly, not yet by using an icon embeded in a dll.\"\n</blockquote>\n\n<blockquote>\n\"Working today on preparing talks and demos for the #ezconference together with\n@bm.\"\n</blockquote>\n\n<p>\nYou can add messages at the home page, or any other page if you are \nlogged in. There are a couple of additional features. The first of them \nis <b>tagging</b>. If you include a word with a # sign before, TheWire will \nautomatically add a tag to the message. In Bårds message above, \n\"#ezconference\" is converted into a tag as you can see here: \n<a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/update/239'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/update/239</a>\n</p>\n\n<h3>Tags</h3>\n\n<p>\nTags should be used to give your message one of more categories. Those could be\n#ezc (eZ Components), #ezp (eZ Publish), #marketing, #ezconference, #fun, #bbq,\nor whatever you want. Tags can later be used to filter out specific content -\nsomething that is extra important for the jabber integration (more follows on\nthat in a bit). Tags can only contain the characters A-Z, a-z, 0-9 and -, and\nhave to start with a letter. You can very easily see all updates for a tag by\ngoing to f.e. <a\nhref='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/tag/ezconference'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/tag/ezconference</a>.\n</p>\n\n<h3>People Tags</h3>\n\n<p>\nAnother convention is that if you talk about people you can use the @ \nsign. A message with the text \"@dr\" will automatically add a link to \nthat person's updates, as you can see in <a\nhref='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/update/240'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/update/240</a>\nwhere @kn links to a list with all of Kore's updates (none right now \nthough). As example see <a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/person/pb'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/person/pb</a>.\n</p>\n\n<h3>Links</h3>\n\n<p>\nNormal links are converted automatically to short links, just like \ntinyurl.com does that. This is because the length of messages is \nrestricted to 250 characters.\n</p>\n<p>\nLinks are automatically created to the public issue tracker by using\n\"issue #12345\" or \"bug #12345\" (without quotes). They will link to ";
$i_output .= htmlspecialchars($this->send->bugLinkFormat);
$i_output .= ".\n</p>\n\n<h2>Informational Pages</h2>\n\n<p>\nThere are a few other pages with information:\n</p>\n\n<ol>\n<li><a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/person/all'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/person/all</a>:\nshows the latest updated info per person to see what they're currently working\non.</li>\n\n<li><a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/prefs'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/prefs</a>: change\nyour password, timezone and whether you want updates through jabber.</li>\n\n<li><a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/rss'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/rss</a>:\nRSS output of the last 25 updates</li>\n\n<li><a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/rss/tag/thewire'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/rss/tag/thewire</a>:\nRSS output of the last 25 updates for the tag \"thewire\" - you can of course use\nany tag you want here.</li>\n</ol>\n\n<h2>Jabber Integration</h2>\n\n<p>\nAnother thing is jabber integration. When you register the mail contains \ninstructions on how to get TheWire added to your jabber account (see below).\nThis allows you to receive updates through jabber (when you've turned it on in\nthe preferences, and your client is not marked as Do Not Disturb). It also\nallows you to send updates on what you're doing to jabber. Just send a message\nto the \"";
$i_output .= htmlspecialchars($this->send->jabberUser);
$i_output .= "\" user. If for some moment you don't want it to\nsend you updates, you can write \"shut up\" or \"stop\" to that user. To enable\nupdates again, you can use \"start\".\n</p>\n\n<p>\nYou don't need VPN to access it, but you do need an account. You can \nregister for an account by going to\n<a href='http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/register'>http://";
$i_output .= htmlspecialchars($this->send->installDomain);
$i_output .= "/register</a>. Fill\nin all the information, and click \"Register\". A password will be generated and\nmailed to your account. In this same mail you can also find instructions on how\nto get jabber integration working. Let me know if you have problems.\n</p>\n\n<h3>Adding TheWire to jabber</h3>\n<p>\nIn order to make jabber integration work, please follow the following\nprocedure:\n</p>\n\n<ol>\n<li>Add the user \"";
$i_output .= htmlspecialchars($this->send->jabberUser);
$i_output .= "@";
$i_output .= htmlspecialchars($this->send->jabberDomain);
$i_output .= "\" to your jabber account.</li>\n<li>Send a message like \"hello\" to this new user.</li>\n<li>The jabber user will now authenticate you and ask for your authorization as well.</li>\n<li>Authorize the jabber user so that it can send messages to you.</li>\n</ol>\n";
return $i_output;
?>
