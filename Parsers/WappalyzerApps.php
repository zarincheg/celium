<?php
namespace Parsers;
/**
* Wappalyzer Apps class
*/
class WappalyzerApps {
	
	public static $categories = array(
		1 => 'CMS',
		2 => 'Forum',
		3 => 'ManagerDB',
		4 => 'Documentation',
		5 => 'Widgets',
		6 => 'InternetShop',
		7 => 'PhotoGalery',
		8 => 'Wiki',
		9 => 'HostingPanel',
		10 => 'Statistic',
		11 => 'Blog',
		12 => 'JavaScriptFrameworks',
		13 => 'BugTrackingSystem',
		14 => 'Video',
		15 => 'SystemComments',
		16 => 'CAPTCHA',
		17 => 'FontCollection',
		18 => 'WebFramework',
		19 => 'Other',
		20 => 'ProgrammCreate',
		21 => 'LMS',
		22 => 'Web-Server',
		24 => 'Editor',
		25 => 'JavascriptGraphics',
		26 => 'MobileFramework',
		27 => 'LanguageDevelopment',
		28 => 'OS',	
	);

	public static $apps = array(
	  '1C-Bitrix' => array(
	    'category' => 1,
	    'html' => '/<link[^>]+components\/bitrix/i',
	    'script' => '/1c\-bitrix/i',
	  ),
	  '2zProject' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/2z project/i',
	    ),
	  ),
	  'AddThis' => array(
	    'category' => 5,
	    'script' => '/addthis\.com\/js/',
	    'env' => '/^addthis$/',
	  ),
	  'AdobeGoLive' => array(
	    'category' => 20,
	    'meta' => array(
	      'generator' => '/Adobe GoLive/i',
	    ),
	  ),
	  'AdvancedWeb Stats' => array(
	    'category' => 10,
	    'html' => '/aws.src = [^<]+caphyon\-analytics/i',
	  ),
	  'AmiroCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Amiro/i',
	    ),
	  ),
	  'AOLserver' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/AOLserver/i',
	    ),
	  ),
	  'Apache' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/(Apache($|[^-])|HTTPD)/i',
	    ),
	  ),
	  'ApacheTomcat' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/Apache-Coyote/i',
	    ),
	  ),
	  'ApacheTraffic Server' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/YTS/i',
	    ),
	  ),
	  'ArcForum' => array(
	    'category' => 2,
	    'html' => '/ping\.src = node\.href;/',
	  ),
	  'Atlassian Confluence' => array(
	    'category' => 8,
	    'html' => '/Powered by <a href=.http:\/\/www\.atlassian\.com\/software\/confluence/i',
	  ),
	  'AtlassianJira' => array(
	    'category' => 13,
	    'html' => '/Powered by <a href=.http:\/\/www\.atlassian\.com\/software\/jira/i',
	  ),
	  'AWStats' => array(
	    'category' => 10,
	    'meta' => array(
	      'generator' => '/AWStats/i',
	    ),
	  ),
	  'Banshee' => array(
	    'category' => 1,
	    'html' => '/Built upon the <a href=("|\')[^>]+banshee-php\.org/i',
	  ),
	  'Backbone.js' => array(
	    'category' => 12,
	    'script' => '/backbone.*\.js/',
	    'env' => '/^Backbone$/',
	  ),
	  'BIGACE' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/BIGACE/',
	    ),
	    'html' => '/Powered by <a href=("|\')[^>]+BIGACE|<!--\s+Site is running BIGACE/i',
	  ),
	  'BigDump' => array(
	    'category' => 3,
	    'html' => '/<!-- <h1>BigDump: Staggered MySQL Dump Importer/',
	  ),
	  'blip.tv' => array(
	    'category' => 14,
	    'html' => '/<(param|embed)[^>]+blip\.tv\/play/i',
	  ),
	  'Blogger' => array(
	    'category' => 11,
	    'meta' => array(
	      'generator' => '/blogger/i',
	    ),
	    'url' => '/^(www.)?.+\.blogspot\.com/i',
	  ),
	  'Bugzilla' => array(
	    'category' => 13,
	    'html' => '/<[^>]+(id|title|name)=("|\')bugzilla/i',
	  ),
	  'Burning Board' => array(
	    'category' => 2,
	    'html' => '/<a href=(\'|")[^>]+woltlab\.com.+Burning Board/i',
	  ),
	  'CakePHP' => array(
	    'category' => 18,
	    'headers' => array(
	      'Set-Cookie' => '/CAKEPHP=/',
	    ),
	    'meta' => array(
	      'application-name' => '/CakePHP/',
	    ),
	  ),
	  'Cargo' => array(
	    'category' => 1,
	    'script' => '/\/cargo\./i',
	    'html' => '/<link [^>]+Cargo feed/',
	  ),
	  'CentOS' => array(
	    'category' => 28,
	    'headers' => array(
	      'Server' => '/CentOS/i',
	      'X-Powered-By' => '/CentOS/i',
	    ),
	  ),
	  'Chameleon' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/chameleon\-cms/i',
	    ),
	  ),
	  'chartbeat' => array(
	    'category' => 10,
	    'html' => '/function loadChartbeat\(\) {/i',
	  ),
	  'Chamilo' => array(
	    'category' => 21,
	    'meta' => array(
	      'generator' => '/Chamilo/i',
	    ),
	    'headers' => array(
	      'X-Powered-By' => '/Chamilo/',
	    ),
	  ),
	  'Cherokee' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/Cherokee/i',
	    ),
	  ),
	  'CKEditor' => array(
	    'category' => 24,
	    'env' => '/^CKEDITOR$/',
	  ),
	  'ClickHeat' => array(
	    'category' => 10,
	    'script' => '/clickheat.*\.js/i',
	    'env' => '/^clickHeatBrowser$/',
	  ),
	  'ClickTale' => array(
	    'category' => 10,
	    'html' => '/if\(typeof ClickTale(Tag)*==("|\')function("|\')\)/',
	    'env' => '/^ClickTale/i',
	  ),
	  'Clicky' => array(
	    'category' => 10,
	    'script' => '/static\.getclicky\.com/',
	    'env' => '/^clicky$/',
	  ),
	  'CMSMadeSimple' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/CMS Made Simple/i',
	    ),
	  ),
	  'CO2Stats' => array(
	    'category' => 10,
	    'html' => '/src=("|\')http:\/\/www\.co2stats\.com\/propres\.php/',
	  ),
	  'CodeIgniter' => array(
	    'category' => 18,
	    'headers' => array(
	      'Set-Cookie' => '/(exp_last_activity|exp_tracker|ci_session)/',
	    ),
	  ),
	  'comScore' => array(
	    'category' => 10,
	    'html' => '/<i{1}frame[^>]* (id=("|\')comscore("|\')|scr=[^>]+comscore)/',
	    'env' => '/^_?COMSCORE$/i',
	  ),
	  'Concrete5' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/concrete5/i',
	    ),
	  ),
	  'Contao' => array(
	    'category' => 1,
	    'html' => '/(<!--\s+This website is powered by (TYPOlight|Contao)|<link[^>]+(typolight|contao).css)/i',
	  ),
	  'Contens' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/contens/i',
	    ),
	  ),
	  'ConversionLab' => array(
	    'category' => 10,
	    'script' => '/conversionlab\.trackset\.com\/track\/tsend\.js/',
	  ),
	  'Coppermine' => array(
	    'category' => 7,
	    'html' => '/<!--Coppermine Photo Gallery/i',
	  ),
	  'Cotonti' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Cotonti/i',
	    ),
	  ),
	  'CouchDB' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/CouchDB/i',
	    ),
	  ),
	  'cPanel' => array(
	    'category' => 9,
	    'html' => '/<!-- cPanel/i',
	  ),
	  'Crazy Egg' => array(
	    'category' => 10,
	    'script' => '/cetrk\.com\/pages\/scripts\/[0-9]+\/[0-9]+\.js/',
	  ),
	  'CS Cart' => array(
	    'category' => 6,
	    'html' => '/&nbsp;Powered by (<a href=.http:\/\/www\.cs\-cart\.com|CS\-Cart)/i',
	  ),
	  'CubeCart' => array(
	    'category' => 6,
	    'html' => '/(Powered by <a href=.http:\/\/www\.cubecart\.com|<p[^>]+>Powered by CubeCart)/i',
	  ),
	  'cufon' => array(
	    'category' => 17,
	    'script' => '/cufon\-yui\.js/',
	    'env' => '/^Cufon$/',
	  ),
	  'd3' => array(
	    'category' => 25,
	    'script' => '/d3(\.min)?\.js/',
	  ),
	  'DanneoCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Danneo/i',
	    ),
	  ),
	  'DataLifeEngine' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/DataLife Engine/i',
	    ),
	  ),
	  'Debian' => array(
	    'category' => 28,
	    'headers' => array(
	      'Server' => '/Debian/i',
	      'X-Powered-By' => '/(Debian|dotdeb|etch|lenny|squeeze|wheezy)/i',
	    ),
	  ),
	  'DHTMLX' => array(
	    'category' => 12,
	    'script' => '/dhtmlxcommon\.js/',
	  ),
	  'DirectAdmin' => array(
	    'category' => 9,
	    'html' => '/<a[^>]+>DirectAdmin<\/a> Web Control Panel/i',
	  ),
	  'Disqus' => array(
	    'category' => 15,
	    'script' => '/disqus_url/',
	    'html' => '/<div[^>]+id=("|\')disqus_thread("|\')/',
	    'env' => '/^DISQUS/i',
	  ),
	  'dojo' => array(
	    'category' => 12,
	    'script' => '/dojo(\.xd)?\.js/',
	    'env' => '/^dojo$/',
	  ),
	  'Dokeos' => array(
	    'category' => 21,
	    'meta' => array(
	      'generator' => '/Dokeos/i',
	    ),
	    'html' => '/Portal <a[^>]+>Dokeos|@import "[^"]+dokeos_blue/i',
	    'headers' => array(
	      'X-Powered-By' => '/Dokeos/',
	    ),
	  ),
	  'DokuWiki' => array(
	    'category' => 8,
	    'meta' => array(
	      'generator' => '/DokuWiki/i',
	    ),
	  ),
	  'DotNetNuke' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/DotNetNuke/i',
	    ),
	    'html' => '/<!\-\- by DotNetNuke Corporation/i',
	  ),
	  'DreamWeaver' => array(
	    'category' => 20,
	    'html' => '/(<!\-\-[^>]*(InstanceBeginEditable|Dreamweaver[^>]+target|DWLayoutDefaultTable)|function MM_preloadImages\(\) {)/',
	  ),
	  'Drupal' => array(
	    'category' => 1,
	    'script' => '/drupal\.js/',
	    'html' => '/(jQuery\.extend\(Drupal\.settings, \{|Drupal\.extend\(\{ settings: \{|<link[^>]+sites\/(default|all)\/themes\/|<style[^>]+sites\/(default|all)\/(themes|modules)\/)/i',
	    'headers' => array(
	      'X-Drupal-Cache' => '/.*/',
	      'X-Generator' => '/Drupal/',
	      'Expires' => '/19 Nov 1978/',
	    ),
	    'env' => '/^Drupal$/',
	  ),
	  'DrupalCommerce' => array(
	    'category' => 6,
	    'html' => '/id\=\"block\-commerce\-cart\-cart|class\=\"commerce\-product\-field/i',
	  ),
	  'Dynamicweb' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Dynamicweb/i',
	    ),
	  ),
	  'e107' => array(
	    'category' => 1,
	    'script' => '/e107\.js/',
	  ),
	  'Exhibit' => array(
	    'category' => 25,
	    'script' => '/exhibit.*\.js/',
	    'env' => '/^Exhibit$/',
	  ),
	  'ExtJS' => array(
	    'category' => 12,
	    'script' => '/ext\-base\.js/',
	    'env' => '/^Ext$/',
	  ),
	  'ExpressionEngine' => array(
	    'category' => 1,
	    'headers' => array(
	      'Set-Cookie' => '/(exp_last_activity|exp_tracker)/',
	    ),
	  ),
	  'eZ Publish' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/eZ Publish/i',
	    ),
	  ),
	  'FluxBB' => array(
	    'category' => 2,
	    'html' => '/Powered by (<strong>)?<a href=("|\')[^>]+fluxbb/i',
	  ),
	  'Flyspray' => array(
	    'category' => 13,
	    'html' => '/(<a[^>]+>Powered by Flyspray|<map id=("|\')projectsearchform)/',
	  ),
	  'FreeBSD' => array(
	    'category' => 28,
	    'headers' => array(
	      'Server' => '/FreeBSD/i',
	    ),
	  ),
	  'FrontPage' => array(
	    'category' => 20,
	    'meta' => array(
	      'generator' => '/Microsoft FrontPage/',
	    ),
	    'html' => '/<html[^>]+urn:schemas\-microsoft\-com:office:office/i',
	  ),
	  'Gauges' => array(
	    'category' => 10,
	    'html' => '/t\.src = \'\/\/secure\.gaug\.es\/track\.js/',
	    'env' => '/^_gauges$/',
	  ),
	  'GetSatisfaction' => array(
	    'category' => 13,
	    'html' => '/var feedback_widget = new GSFN\.feedback_widget\(feedback_widget_options\)/',
	  ),
	  'GoogleAnalytics' => array(
	    'category' => 10,
	    'html' => '/(google\-analytics\.com\/ga\.js|google-analytics\.com\/urchin\.js)/',
	    'env' => '/^gaGlobal$/',
	  ),
	  'Google App Engine' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/Google Frontend/i',
	    ),
	  ),
	  'GoogleFontAPI' => array(
	    'category' => 17,
	    'html' => '/<link[^>]* href=("|\')http:\/\/fonts\.googleapis\.com/',
	  ),
	  'GoogleFriendConnect' => array(
	    'category' => 5,
	    'script' => '/google.com\/friendconnect/',
	  ),
	  'GoogleMaps' => array(
	    'category' => 5,
	    'script' => '/(maps\.google\.com\/maps\?file=api|maps\.google\.com\/maps\/api\/staticmap)/',
	  ),
	  'GoogleSites' => array(
	    'category' => 1,
	    'url' => '/sites.google.com/',
	  ),
	  'GraffitiCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Graffiti CMS/i',
	    ),
	  ),
	  'Gravatar' => array(
	    'category' => 19,
	    'env' => '/^Gravatar$/',
	  ),
	  'GravityInsights' => array(
	    'category' => 10,
	    'html' => '/gravityInsightsParams\.site_guid = \'/',
	    'env' => '/^GravityInsights$/',
	  ),
	  'Handlebars' => array(
	    'category' => 12,
	    'env' => '/^Handlebars$/',
	  ),
	  'Hiawatha' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/Hiawatha/i',
	    ),
	  ),
	  'Highcharts' => array(
	    'category' => 25,
	    'script' => '/highcharts.*\.js/',
	    'env' => '/^Highcharts$/',
	  ),
	  'HotaruCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Hotaru CMS/i',
	    ),
	  ),
	  'IIS' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/IIS/i',
	    ),
	  ),
	  'Indexhibit' => array(
	    'category' => 1,
	    'html' => '/<link [^>]+ndxz-studio/i',
	  ),
	  'InstantCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/InstantCMS/i',
	    ),
	  ),
	  'IPB' => array(
	    'category' => 2,
	    'script' => '/jscripts\/ips_/',
	  ),
	  'iWeb' => array(
	    'category' => 20,
	    'meta' => array(
	      'generator' => '/iWeb/i',
	    ),
	  ),
	  'Jalios' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Jalios/i',
	    ),
	  ),
	  'JavascriptInfovisToolkit' => array(
	    'category' => 25,
	    'script' => '/jit.*\.js/',
	    'env' => '/^\$jit$/',
	  ),
	  'Jo' => array(
	    'category' => 26,
	    'script' => '/[^a-z]?jo(\.[^>]+)?\.js/i',
	  ),
	  'Joomla' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Joomla/i',
	    ),
	    'html' => '/(<!\-\- JoomlaWorks "K2"|<[^>]+(feed|components)\/com_)/i',
	    'headers' => array(
	      'X-Content-Encoded-By' => '/Joomla/',
	    ),
	    'env' => '/^(jcomments)$/i',
	  ),
	  'jqPlot' => array(
	    'category' => 25,
	    'script' => '/jqplot.*\.js/',
	    'env' => '/^jQuery.jqplot$/',
	  ),
	  'jQTouch' => array(
	    'category' => 26,
	    'script' => '/jqtouch.*\.js/i',
	    'env' => '/^jQT$/',
	  ),
	  'jQueryUI' => array(
	    'category' => 12,
	    'script' => '/jquery\-ui.*\.js/',
	  ),
	  'jQuery' => array(
	    'category' => 12,
	    'script' => '/jquery.*.js/',
	    'env' => '/^jQuery$/',
	  ),
	  'jQueryMobile' => array(
	    'category' => 26,
	    'script' => '/jquery\.mobile.*\.js/i',
	  ),
	  'jQuerySparklines' => array(
	    'category' => 25,
	    'script' => '/jquery\.sparkline.*\.js/i',
	  ),
	  'JSCharts' => array(
	    'category' => 25,
	    'script' => '/jscharts.*\.js/i',
	    'env' => '/^JSChart$/',
	  ),
	  'JTLShop' => array(
	    'category' => 6,
	    'html' => '/(<input[^>]+name=(\'|")JTLSHOP|<a href=(\'|")jtl\.php)/i',
	  ),
	  'K2' => array(
	    'category' => 19,
	    'html' => '/<!\-\- JoomlaWorks "K2"/',
	  ),
	  'Kampyle' => array(
	    'category' => 10,
	    'script' => '/cf\.kampyle\.com\/k_button\.js/',
	  ),
	  'Kentico CMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Kentico CMS/i',
	    ),
	  ),
	  'Koego' => array(
	    'category' => 10,
	    'script' => '/tracking\.koego\.com\/end\/ego\.js/',
	  ),
	  'Kohana' => array(
	    'category' => 18,
	    'headers' => array(
	      'Set-Cookie' => '/kohanasession/i',
	      'X-Powered-By' => '/Kohana/',
	    ),
	  ),
	  'KolibriCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Kolibri/i',
	    ),
	  ),
	  'Koobi' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Koobi/i',
	    ),
	  ),
	  'lighttpd' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/lighttpd/i',
	    ),
	  ),
	  'LiveJournal' => array(
	    'category' => 11,
	    'url' => '/^(www.)?.+\.livejournal\.com/i',
	  ),
	  'LotusDomino' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/Lotus\-Domino/i',
	    ),
	  ),
	  'Magento' => array(
	    'category' => 6,
	    'script' => '/\/(js\/mage|skin\/frontend\/(default|enterprise))\//',
	    'env' => '/^(Mage|VarienForm)$/',
	  ),
	  'Mambo' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Mambo/i',
	    ),
	  ),
	  'MantisBT' => array(
	    'category' => 13,
	    'html' => '/<img[^>]+ alt=("|\')Powered by Mantis Bugtracker/i',
	  ),
	  'MaxSiteCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/MaxSite CMS/i',
	    ),
	  ),
	  'MediaWiki' => array(
	    'category' => 8,
	    'meta' => array(
	      'generator' => '/MediaWiki/i',
	    ),
	    'html' => '/(<a[^>]+>Powered by MediaWiki<\/a>|<[^>]+id=("|\')t\-specialpages)/i',
	  ),
	  'Meebo' => array(
	    'category' => 5,
	    'html' => '/(<iframe id=("|\')meebo\-iframe("|\')|Meebo\(\'domReady\'\))/',
	  ),
	  'MicrosoftASP_NET' => array(
	    'category' => 18,
	    'html' => '/<input[^>]+name=("|\')__VIEWSTATE/',
	    'headers' => array(
	      'X-Powered-By' => '/ASP\.NET/',
	      'X-AspNet-Version' => '/.+/',
	    ),
	  ),
	  'MicrosoftSharePoint' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Microsoft SharePoint/i',
	    ),
	    'headers' => array(
	      'MicrosoftSharePointTeamServices' => '/.*/',
	      'X-SharePointHealthScore' => '/.*/',
	      'SPRequestGuid' => '/.*/',
	    ),
	  ),
	  'MiniBB' => array(
	    'category' => 2,
	    'html' => '/<a href=("|\')[^>]+minibb.+\s+<!--End of copyright link/i',
	  ),
	  'Mint' => array(
	    'category' => 10,
	    'script' => '/mint\/\?js/',
	    'env' => '/^Mint$/',
	  ),
	  'Mixpanel' => array(
	    'category' => 10,
	    'script' => '/api\.mixpanel\.com\/track/',
	  ),
	  'MochiKit' => array(
	    'category' => 12,
	    'script' => '/MochiKit\.js/',
	    'env' => '/^MochiKit$/',
	  ),
	  'Modernizr' => array(
	    'category' => 12,
	    'script' => '/modernizr.*\.js/',
	    'env' => '/^Modernizr$/',
	  ),
	  'MODx' => array(
	    'category' => 1,
	    'html' => '/(<a[^>]+>Powered by MODx<\/a>|var el= \$\(\'modxhost\'\);|<script type=("|\')text\/javascript("|\')>var MODX_MEDIA_PATH = "media";)|<(link|script)[^>]+assets\/snippets\//i',
	  ),
	  'Mollom' => array(
	    'category' => 16,
	    'script' => '/mollom\.js/',
	    'html' => '/<img[^>]+\/.mollom\/.com/i',
	  ),
	  'Mongrel' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/Mongrel/',
	    ),
	  ),
	  'Moodle' => array(
	    'category' => 21,
	    'html' => '/(var moodleConfigFn = function\(me\)|<img[^>]+moodlelogo)/i',
	  ),
	  'Moogo' => array(
	    'category' => 1,
	    'script' => '/kotisivukone.js/',
	  ),
	  'MooTools' => array(
	    'category' => 12,
	    'script' => '/mootools.*\.js/',
	    'env' => '/^MooTools$/',
	  ),
	  'MovableType' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Movable Type/i',
	    ),
	  ),
	  'Mustache' => array(
	    'category' => 12,
	    'env' => '/^Mustache$/',
	  ),
	  'MyBB' => array(
	    'category' => 2,
	    'html' => '/(<script [^>]+\s+<!--\s+lang\.no_new_posts|<a[^>]* title=("|\')Powered By MyBB)/i',
	  ),
	  'MyBlogLog' => array(
	    'category' => 5,
	    'script' => '/pub\.mybloglog\.com/i',
	  ),
	  'Mynetcap' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Mynetcap/i',
	    ),
	  ),
	  'Nedstat' => array(
	    'category' => 10,
	    'html' => '/sitestat\(("|\')http:\/\/nl\.sitestat\.com/',
	  ),
	  'Nginx' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/nginx/i',
	    ),
	  ),
	  'NOIX' => array(
	    'category' => 19,
	    'html' => '/<[^>]+(src|href)=[^>]*(\/media\/noix)|<!\-\- NOIX/i',
	  ),
	  'nopCommerce' => array(
	    'category' => 6,
	    'html' => '/(<!\-\-Powered by nopCommerce|Powered by: <a[^>]+nopcommerce)/i',
	  ),
	  'OneStat' => array(
	    'category' => 10,
	    'html' => '/var p=("|\')http("|\')\+\(d\.URL\.indexOf\(\'https:\'\)==0\?\'s\':\'\'\)\+("|\'):\/\/stat\.onestat\.com\/stat\.aspx\?tagver/i',
	  ),
	  'OpenCart' => array(
	    'category' => 6,
	    'html' => '/(Powered By <a href=("|\')[^>]+OpenCart|route = getURLVar\(("|\')route)/i',
	  ),
	  'openEngine' => array(
	    'category' => 1,
	    'html' => '/<meta[^>]+openEngine/i',
	  ),
	  'OpenGSE' => array(
	    'category' => 22,
	    'headers' => array(
	      'Server' => '/GSE/i',
	    ),
	  ),
	  'OpenLayers' => array(
	    'category' => 5,
	    'script' => '/openlayers/',
	    'env' => '/^OpenLayers$/',
	  ),
	  'Optimizely' => array(
	    'category' => 10,
	    'env' => '/^optimizely/',
	  ),
	  'osCommerce' => array(
	    'category' => 6,
	    'html' => '/<!-- header_eof \/\/-->|<a[^>]*(osCsid|cPath)/i',
	  ),
	  'osCSS' => array(
	    'category' => 6,
	    'html' => '/<body onload=("|\')window\.defaultStatus=\'oscss templates\';("|\')/i',
	  ),
	  'PANSITE' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/PANSITE/i',
	    ),
	  ),
	  'papayaCMS' => array(
	    'category' => 1,
	    'html' => '/<link[^>]*\/papaya-themes\//i',
	  ),
	  'Parsely' => array(
	    'category' => 10,
	    'env' => '/^PARSELY$/',
	  ),
	  'PHP' => array(
	    'category' => 27,
	    'headers' => array(
	      'Server' => '/php/i',
	      'X-Powered-By' => '/php/i',
	      'Set-Cookie' => '/PHPSESSID/',
	    ),
	    'url' => '/\.php$/',
	  ),
	  'PHP-Fusion' => array(
	    'category' => 1,
	    'html' => '/Powered by <a href=("|\')[^>]+php-fusion/i',
	  ),
	  'PHP-Nuke' => array(
	    'category' => 2,
	    'meta' => array(
	      'generator' => '/PHP-Nuke/i',
	    ),
	    'html' => '/<[^>]+Powered by PHP\-Nuke/i',
	  ),
	  'phpBB' => array(
	    'category' => 2,
	    'meta' => array(
	      'copyright' => '/phpBB Group/',
	    ),
	    'html' => '/Powered by (<a href=("|\')[^>]+)?phpBB/i',
	  ),
	  'phpDocumentor' => array(
	    'category' => 4,
	    'html' => '/<!-- Generated by phpDocumentor/',
	  ),
	  'phpMyAdmin' => array(
	    'category' => 3,
	    'html' => '/(var pma_absolute_uri = \'|PMA_sendHeaderLocation\(|<title>phpMyAdmin<\/title>)/i',
	  ),
	  'phpPgAdmin' => array(
	    'category' => 3,
	    'html' => '/(<title>phpPgAdmin<\/title>|<span class=("|\')appname("|\')>phpPgAdmin)/i',
	  ),
	  'Piwik' => array(
	    'category' => 10,
	    'html' => '/var piwikTracker = Piwik\.getTracker\(/i',
	    'env' => '/^Piwik$/',
	  ),
	  'Plesk' => array(
	    'category' => 9,
	    'script' => '/common\.js\?plesk/i',
	  ),
	  'Plone' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Plone/i',
	    ),
	  ),
	  'Plura' => array(
	    'category' => 19,
	    'html' => '/<iframe src="http:\/\/pluraserver\.com/',
	  ),
	  'posterous' => array(
	    'category' => 1,
	    'html' => '/<div class=("|\')posterous/i',
	    'env' => '/^Posterous/i',
	  ),
	  'Prestashop' => array(
	    'category' => 6,
	    'meta' => array(
	      'generator' => '/PrestaShop/i',
	    ),
	    'html' => '/Powered by <a href=("|\')[^>]+PrestaShop/i',
	  ),
	  'Prototype' => array(
	    'category' => 12,
	    'script' => '/(prototype|protoaculous)\.js/',
	    'env' => '/^Prototype$/',
	  ),
	  'Protovis' => array(
	    'category' => 25,
	    'script' => '/protovis.*\.js/',
	    'env' => '/^protovis$/',
	  ),
	  'punBB' => array(
	    'category' => 2,
	    'html' => '/Powered by <a href=("|\')[^>]+punbb/i',
	  ),
	  'Quantcast' => array(
	    'category' => 10,
	    'script' => '/edge\.quantserve\.com\/quant\.js/',
	    'env' => '/^quantserve$/',
	  ),
	  'QuickCart' => array(
	    'category' => 6,
	    'html' => '/<a href="[^>]+opensolution\.org\/">Powered by/i',
	  ),
	  'RedHat' => array(
	    'category' => 28,
	    'headers' => array(
	      'Server' => '/Red Hat/i',
	      'X-Powered-By' => '/Red Hat/i',
	    ),
	  ),
	  'Raphael' => array(
	    'category' => 25,
	    'script' => '/raphael.*\.js/',
	    'env' => '/^Raphael$/',
	  ),
	  'reCAPTCHA' => array(
	    'category' => 16,
	    'script' => '/(api\-secure\.recaptcha\.net|recaptcha_ajax\.js)/',
	    'html' => '/<div[^>]+id=("|\')recaptcha_image/',
	    'env' => '/^Recaptcha$/',
	  ),
	  'Reddit' => array(
	    'category' => 2,
	    'html' => '/(<script[^>]+>var reddit = {|<a[^>]+Powered by Reddit|powered by <a[^>]+>reddit<)/i',
	    'url' => '/^(www.)?reddit\.com/',
	  ),
	  'Redmine' => array(
	    'category' => 13,
	    'meta' => array(
	      'description' => '/Redmine/i',
	    ),
	    'html' => '/Powered by <a href=("|\')[^>]+Redmine/i',
	  ),
	  'Reinvigorate' => array(
	    'category' => 10,
	    'html' => '/reinvigorate\.track\("/',
	  ),
	  'RequireJS' => array(
	    'category' => 12,
	    'script' => '/require.*\.js/',
	    'env' => '/^requirejs$/',
	  ),
	  'Ruby' => array(
	    'category' => 27,
	    'headers' => array(
	      'Server' => '/(Mongrel|WEBrick|Ruby|mod_rails|mod_rack|Phusion.Passenger)/i',
	      'X-Powered-By' => '/(mod_rails|mod_rack|Phusion.Passenger)/i',
	    ),
	  ),
	  'S.Builder' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/S\.Builder/i',
	    ),
	  ),
	  's9y' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Serendipity/i',
	    ),
	  ),
	  'script_aculo_us' => array(
	    'category' => 12,
	    'script' => '/(scriptaculous|protoaculous)\.js/',
	    'env' => '/^Scriptaculous$/',
	  ),
	  'SenchaTouch' => array(
	    'category' => 26,
	    'script' => '/sencha\-touch.*\.js/',
	  ),
	  'ShareThis' => array(
	    'category' => 5,
	    'script' => '/w\.sharethis\.com\//',
	    'env' => '/^SHARETHIS$/',
	  ),
	  'sIFR' => array(
	    'category' => 17,
	    'script' => '/sifr\.js/',
	  ),
	  'SiteMeter' => array(
	    'category' => 10,
	    'script' => '/sitemeter.com\/js\/counter\.js\?site=/',
	  ),
	  'SiteCatalyst' => array(
	    'category' => 10,
	    'html' => '/var s_code=s\.t\(\);if\(s_code\)document\.write\(s_code\)/i',
	  ),
	  'SiteEdit' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/SiteEdit/i',
	    ),
	  ),
	  'SMF' => array(
	    'category' => 2,
	    'html' => '/<script [^>]+\s+var smf_/i',
	  ),
	  'sNews' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/sNews/',
	    ),
	  ),
	  'Snoobi' => array(
	    'category' => 10,
	    'script' => '/snoobi\.com\/snoop\.php/',
	  ),
	  'SOBI2' => array(
	    'category' => 19,
	    'html' => '/(<!\-\- start of Sigsiu Online Business Index|<div[^>]* class=("|\')sobi2)/i',
	  ),
	  'SoundManager' => array(
	    'category' => 12,
	    'env' => '/^(SoundManager|BaconPlayer)$/',
	  ),
	  'SPIP' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/SPIP/i',
	    ),
	    'headers' => array(
	      'X-Spip-Cache' => '/.*/',
	    ),
	  ),
	  'SQLBuddy' => array(
	    'category' => 3,
	    'html' => '/(<title>SQL Buddy<\/title>|<[^>]+onclick=("|\')sideMainClick\(("|\')home\.php)/i',
	  ),
	  'Squarespace' => array(
	    'category' => 1,
	    'html' => '/Squarespace\.Constants\.CURRENT_MODULE_ID/i',
	  ),
	  'SquizMatrix' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Squiz Matrix/',
	    ),
	    'html' => '/  Running (MySource|Squiz) Matrix/i',
	    'X-Powered-By' => '/Squiz Matrix/',
	  ),
	  'StatCounter' => array(
	    'category' => 10,
	    'script' => '/statcounter\.com\/counter\/counter/',
	  ),
	  'SWFObject' => array(
	    'category' => 19,
	    'script' => '/swfobject.*\.js/i',
	    'env' => '/^SWFObject$/',
	  ),
	  'swift_engine' => array(
	    'category' => 1,
	    'headers' => array(
	      'X-Powered-By' => '/swift\.engine/',
	    ),
	  ),
	  'Swiftlet' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Swiftlet/i',
	    ),
	    'html' => '/Powered by <a href=("|\')[^>]+Swiftlet/i',
	    'headers' => array(
	      'X-Swiftlet-Cache' => '/.*/',
	      'X-Powered-By' => '/Swiftlet/',
	    ),
	  ),
	  'TextpatternCMS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/Textpattern/i',
	    ),
	  ),
	  'Timeline' => array(
	    'category' => 25,
	    'script' => '/timeline.*\.js/',
	    'env' => '/^Timeline$/',
	  ),
	  'Timeplot' => array(
	    'category' => 25,
	    'script' => '/timeplot.*\.js/',
	    'env' => '/^Timeplot$/',
	  ),
	  'TinyMCE' => array(
	    'category' => 24,
	    'env' => '/^tinyMCE$/',
	  ),
	  'TomatoCart' => array(
	    'category' => 6,
	    'meta' => array(
	      'generator' => '/TomatoCart/i',
	    ),
	  ),
	  'Trac' => array(
	    'category' => 13,
	    'html' => '/(<a id=("|\')tracpowered)/i',
	  ),
	  'Tumblr' => array(
	    'category' => 11,
	    'html' => '/<iframe src=("|\')http:\/\/www\.tumblr\.com/i',
	    'url' => '/^(www.)?.+\.tumblr\.com/i',
	    'headers' => array(
	      'X-Tumblr-Usec' => '/.*/',
	    ),
	  ),
	  'TwilightCMS' => array(
	    'category' => 1,
	    'headers' => array(
	      'X-Powered-CMS' => '/Twilight CMS/',
	    ),
	  ),
	  'TwitterBootstrap' => array(
	    'category' => 18,
	    'script' => '/twitter\.github\.com\/bootstrap/',
	    'html' => '/<link[^>]+bootstrap[^>]+css/',
	    'env' => '/^Twipsy$/',
	  ),
	  'Typekit' => array(
	    'category' => 17,
	    'script' => '/use.typekit.com/',
	    'env' => '/^Typekit$/',
	  ),
	  'TypePad' => array(
	    'category' => 11,
	    'meta' => array(
	      'generator' => '/typepad/i',
	    ),
	    'url' => '/^(www.)?.+\.typepad\.com/i',
	  ),
	  'TYPO3' => array(
	    'category' => 1,
	    'headers' => array(
	      'Set-Cookie' => '/fe_typo_user/',
	    ),
	    'meta' => array(
	      'generator' => '/TYPO3/i',
	    ),
	    'html' => '/(<(script[^>]* src|link[^>]* href)=[^>]*fileadmin|<!--TYPO3SEARCH)/i',
	    'url' => '/\/typo3/i',
	  ),
	  'Ubercart' => array(
	    'category' => 6,
	    'script' => '/uc_cart\/uc_cart_block\.js/',
	  ),
	  'Ubuntu' => array(
	    'category' => 28,
	    'headers' => array(
	      'Server' => '/Ubuntu/i',
	      'X-Powered-By' => '/Ubuntu/i',
	    ),
	  ),
	  'Umbraco' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/umbraco/i',
	    ),
	    'headers' => array(
	      'X-Umbraco-Version' => '/.+/',
	    ),
	    'html' => '/powered by <a href=[^>]+umbraco/i',
	  ),
	  'Underscore_js' => array(
	    'category' => 12,
	    'script' => '/underscore.*\.js/',
	  ),
	  'UNIX' => array(
	    'category' => 28,
	    'headers' => array(
	      'Server' => '/Unix/i',
	    ),
	  ),
	  'UserRules' => array(
	    'category' => 13,
	    'html' => '/var _usrp =/',
	    'env' => '/^\_usrp$/',
	  ),
	  'UserVoice' => array(
	    'category' => 13,
	    'env' => '/^UserVoice$/',
	  ),
	  'Vanilla' => array(
	    'category' => 2,
	    'html' => '/<body id=("|\')(DiscussionsPage|vanilla)/i',
	    'headers' => array(
	      'X-Powered-By' => '/Vanilla/',
	    ),
	  ),
	  'Varnish' => array(
	    'category' => 22,
	    'headers' => array(
	      'X-Varnish' => '/.+/',
	      'X-Varnish-Age' => '/.+/',
	      'X-Varnish-Cache' => '/.+/',
	      'X-Varnish-Action' => '/.+/',
	      'X-Varnish-Hostname' => '/.+/',
	      'Via' => '/Varnish/i',
	    ),
	  ),
	  'vBulletin' => array(
	    'category' => 2,
	    'meta' => array(
	      'generator' => '/vBulletin/i',
	    ),
	  ),
	  'viennaCMS' => array(
	    'category' => 1,
	    'html' => '/powered by <a href=("|\')[^>]+viennacms/i',
	  ),
	  'Vignette' => array(
	    'category' => 1,
	    'html' => '/<[^>]+?=("|\')(vgn\-ext|vgnext)/i',
	  ),
	  'Vimeo' => array(
	    'category' => 14,
	    'html' => '/<(param|embed)[^>]+vimeo\.com\/moogaloop/i',
	  ),
	  'VirtueMart' => array(
	    'category' => 6,
	    'html' => '/<div id=("|\')vmMainPage/',
	  ),
	  'VisualPath' => array(
	    'category' => 10,
	    'script' => '/visualpath[^\/]*\.trackset\.it\/[^\/]+\/track\/include\.js/',
	  ),
	  'Vox' => array(
	    'category' => 11,
	    'url' => '/^(www.)?.+\.vox\.com/i',
	  ),
	  'VP-ASP' => array(
	    'category' => 6,
	    'script' => '/vs350\.js/',
	    'html' => '/<a[^>]+>Powered By VP\-ASP Shopping Cart<\/a>/',
	  ),
	  'W3Counter' => array(
	    'category' => 10,
	    'script' => '/w3counter\.com\/tracker\.js/',
	  ),
	  'Web Optimizer' => array(
	    'category' => 10,
	    'html' => '/<title [^>]*lang=("|\')wo("|\')>/',
	  ),
	  'webEdition' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/webEdition/i',
	      'DC.title' => '/webEdition/i',
	    ),
	  ),
	  'WebGUI' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/WebGUI/i',
	    ),
	  ),
	  'WebPublisher' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/WEB\|Publisher/i',
	    ),
	  ),
	  'WebsiteBaker' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/WebsiteBaker/i',
	    ),
	  ),
	  'Webtrekk' => array(
	    'category' => 10,
	    'html' => '/var webtrekk = new Object/',
	  ),
	  'Webtrends' => array(
	    'category' => 10,
	    'html' => '/<img[^>]+id=("|\')DCSIMG("|\')[^>]+webtrends/i',
	    'env' => '/^(WTOptimize|WebTrends)/i',
	  ),
	  'Weebly' => array(
	    'category' => 1,
	    'html' => '/<[^>]+class=("|\')weebly/i',
	  ),
	  'WikkaWiki' => array(
	    'category' => 8,
	    'meta' => array(
	      'generator' => '/WikkaWiki/',
	    ),
	    'html' => '/Powered by <a href=("|\')[^>]+WikkaWiki/i',
	  ),
	  'wink' => array(
	    'category' => 26,
	    'script' => '/(\_base\/js\/base|wink).*\.js/i',
	    'env' => '/^wink$/',
	  ),
	  'WolfCMS' => array(
	    'category' => 1,
	    'html' => '/<a href=("|\')[^>]+wolfcms.org.+Wolf CMS.+inside/i',
	  ),
	  'Woopra' => array(
	    'category' => 10,
	    'script' => '/static\.woopra\.com/',
	  ),
	  'WordPress' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/WordPress/i',
	    ),
	    'html' => '/<link rel=("|\')stylesheet("|\') [^>]+wp-content/i',
	    'env' => '/^wp_username$/',
	  ),
	  'xajax' => array(
	    'category' => 12,
	    'script' => '/xajax_core.*\.js/i',
	  ),
	  'XenForo' => array(
	    'category' => 2,
	    'html' => '/(jQuery\.extend\(true, XenForo|Forum software by XenForo&trade;|<!\-\-XF:branding)/',
	  ),
	  'XiTi' => array(
	    'category' => 10,
	    'html' => '/<[^>]+src=("|\')[^>]+xiti.com\/hit.xiti/i',
	  ),
	  'XMB' => array(
	    'category' => 2,
	    'html' => '/<!-- Powered by XMB/i',
	  ),
	  'xui' => array(
	    'category' => 26,
	    'script' => '/[^a-zA-Z]xui.*\.js/i',
	    'env' => '/^xui$/',
	  ),
	  'XOOPS' => array(
	    'category' => 1,
	    'meta' => array(
	      'generator' => '/XOOPS/i',
	    ),
	  ),
	  'xtCommerce' => array(
	    'category' => 6,
	    'meta' => array(
	      'generator' => '/xt:Commerce/',
	    ),
	    'html' => '/<div class=("|\')copyright("|\')>.+<a[^>]+>xt:Commerce/i',
	  ),
	  'YaBB' => array(
	    'category' => 2,
	    'html' => '/Powered by <a href=("|\')[^>]+yabbforum/i',
	  ),
	  'YahooWebAnalytics' => array(
	    'category' => 10,
	    'script' => '/d\.yimg\.com\/mi\/ywa\.js/',
	  ),
	  'YandexMetrika' => array(
	    'category' => 10,
	    'script' => '/mc\.yandex\.ru\/metrika\/watch\.js/',
	  ),
	  'YouTube' => array(
	    'category' => 14,
	    'html' => '/<(param|embed)[^>]+youtube\.com\/v/i',
	  ),
	  'YUIDoc' => array(
	    'category' => 4,
	    'html' => '/<html[^>]* yuilibrary\.com\/rdf\/[0-9.]+\/yui\.rdf/i',
	  ),
	  'YUI' => array(
	    'category' => 12,
	    'script' => '/\/yui\/|yui\.yahooapis\.com/',
	    'env' => '/^YAHOO$/',
	  ),
	  'ZenCart' => array(
	    'category' => 6,
	    'meta' => array(
	      'generator' => '/Zen Cart/i',
	    ),
	  ),
	  'Zend' => array(
	    'category' => 22,
	    'headers' => array(
	      'X-Powered-By' => '/Zend/',
	    ),
	  ),
	  'Zepto' => array(
	    'category' => 12,
	    'script' => '/zepto.*.js/',
	    'env' => '/^Zepto$/',
	  ),
	);
}