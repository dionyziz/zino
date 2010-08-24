<xsl:variable name="mastertemplate">
    <xsl:value-of select="/*[1]/@resource" />.<xsl:value-of select="/*[1]/@method" />
</xsl:variable>
<xsl:variable name="resource">
    <xsl:value-of select="/*[1]/@resource" />
</xsl:variable>
<xsl:variable name="method">
    <xsl:value-of select="/*[1]/@method" />
</xsl:variable>

<xsl:variable name="user" select="/*[1]/@for" />
<xsl:variable name="sandbox" select="contains( /social/@generator, 'http://beta.zino.gr/' )" />

<xsl:template match="/" priority="1">
    <xsl:choose>
        <!-- tiny master templates -->
        <xsl:when test="$resource = 'session'"><xsl:apply-templates /></xsl:when>
        
        <!-- full master templates -->
        <xsl:otherwise>
            <xsl:call-template name="html" />
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<!--eat all other output-->
<xsl:template match="*|text()" priority="-1"/>

<xsl:template name="html">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <xsl:attribute name="id"><xsl:value-of select="/social/@resource" />-<xsl:value-of select="/social/@method" /></xsl:attribute>
        <head>
            <title><xsl:call-template name="title" /></title>
            <base><xsl:attribute name="href"><xsl:value-of select="/social[1]/@generator" />/</xsl:attribute></base>
            <xsl:choose>
                <xsl:when test="$sandbox">
                    <link type="text/css" href="global.css" rel="stylesheet" />
                </xsl:when>
                <xsl:otherwise>
                    <link type="text/css" href="http://static.zino.gr/css/global.css?10" rel="stylesheet" />
                </xsl:otherwise>
            </xsl:choose>
            <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
            <script type="text/javascript">
              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', 'UA-1065489-1']);
              _gaq.push(['_trackPageview']);

              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();
            </script>
        </head>
        <body onload="Comet.OnBodyLoaded()">
            <div id="world">
                <xsl:call-template name="banner" />
                <div id="content">
                    <xsl:apply-templates />
                </div>
            </div>
            <xsl:choose>
                <xsl:when test="$sandbox">
                    <script type="text/javascript" src="global.js"></script>
                </xsl:when>
                <xsl:otherwise>
                    <script type="text/javascript" src="http://static.zino.gr/js/global.js?7"></script>
                </xsl:otherwise>
            </xsl:choose>
            <script type="text/javascript">
                <xsl:if test="/social/@for">
                    var User = '<xsl:value-of select="/social/@for" />';
                </xsl:if>
                var Now = '<xsl:value-of select="/social/@generated" />';
                var NowDate = stringToDate( Now );
                setInterval( function(){
                    NowDate.setSeconds( NowDate.getSeconds() + 5 );
                    Now = dateToString( NowDate );
                }, 5 * 1000 );
                
                var XMLData = {
                    author: '<xsl:value-of select="/social/*/author/name" />'
                }

                $.ajaxSetup( {
                    dataType: 'xml'
                } );
                _aXSLT.defaultStylesheet = 'global.xsl';
                if ( window.ActiveXObject ) {
                    _aXSLT.ROOT_PATH = '*[1]';
                }

                var Routing = {
                    'photo.view': PhotoView,
                    'photo.listing': PhotoListing,
                    'album.view': PhotoListing, //not sure if it's as supposed to
                    'news.listing': News,
                    'poll.view': Poll,
                    'journal.view': Journal,
                    'user.view': Profile,
                    'favourite.listing': Favourite,
                    'friendship.listing': Friends,
                    'ban.listing': Admin.Banlist
                };
                var MasterTemplate = '<xsl:value-of select="$mastertemplate" />';
                if ( typeof Routing[ MasterTemplate ] != 'undefined' ) {
                    // this must run asap after the body has been downloaded
                    // as this fills in page elements and until it is run
                    // it appears to the user as if the page is still loading
                    Routing[ MasterTemplate ].Init();
                }
                $( function () {
                    // delay these until page has loaded completely
                    // these less important things can be loaded right
                    // afterwards to make sure the user experiences
                    // a faster loading time of more important page elements
                    $( '.time' ).live( 'load', function () {
                        Kamibu.TimeFollow( this );
                    } ).load();

                    Notifications.Check();
                    Presence.Init();
                    Chat.Init();
                    Admin.Bar.Init();
                } );
            </script>
        </body>
    </html>
</xsl:template> 
