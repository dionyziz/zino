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

<xsl:template name="title">
    <xsl:choose>
        <xsl:when test="/social/@resource = 'user' and /social/@method = 'view'">
            <xsl:value-of select="/social/user/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'photo' and /social/@method = 'view'">
            <xsl:choose>
                <xsl:when test="/social/photo/title">
                    <xsl:value-of select="/social/photo/title" />
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="/social/photo/author/name" />
                </xsl:otherwise>
            </xsl:choose>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'poll' and /social/@method = 'view'">
            "<xsl:value-of select="/social/poll/title" />"
            <xsl:choose>
                <xsl:when test="/social/poll/author/gender = 'f'">
                    <xsl:text> της </xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text> του </xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/poll/author/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'journal' and /social/@method = 'view'">
            "<xsl:value-of select="/social/journal/title" />"
            <xsl:choose>
                <xsl:when test="/social/journal/author/gender = 'f'">
                    <xsl:text> της </xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text> του </xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/journal/author/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'news' and /social/@method = 'listing'">
            Νέα στο zino
        </xsl:when>
        <xsl:when test="/social/@resource = 'journal' and /social/@method = 'listing'">
            Ημερολόγια 
            <xsl:if test="/social/journals/author">
                <xsl:choose>
                    <xsl:when test="/social/journals/author/gender = 'f'">
                        <xsl:text> της </xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> του </xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="/social/journals/author/name" />
            </xsl:if>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'poll' and /social/@method = 'listing'">
            Δημοσκοπήσεις
            <xsl:if test="/social/polls/author">
                <xsl:choose>
                    <xsl:when test="/social/polls/author/gender = 'f'">
                        <xsl:text> της </xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> του </xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="/social/polls/author/name" />
            </xsl:if>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'photo' and /social/@method = 'listing'">
            Εικόνες
            <xsl:if test="/social/photos/author">
                <xsl:choose>
                    <xsl:when test="/social/photos/author/gender = 'f'">
                        <xsl:text> της </xsl:text>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text> του </xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="/social/photos/author/name" />
            </xsl:if>
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:when test="/social/@resource = 'favourite' and /social/@method = 'listing'">
            Αγαπημένα
            <xsl:choose>
                <xsl:when test="/social/photos/author/gender = 'f'">
                    <xsl:text> της </xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text> του </xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="/social/photos/author/name" />
            <xsl:text> στο zino</xsl:text>
        </xsl:when>
        <xsl:otherwise>
            zino
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
            <link type="text/css" href="global.css?1" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/spriting/sprite1.css" rel="stylesheet" />
            <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
			<script type="text/javascript" src="global.js?1"></script>
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
            </script>
        </head>
        <body onload="Comet.OnBodyLoaded()">
            <div id="world">
                <xsl:call-template name="banner" />
                <div id="content">
                    <xsl:apply-templates />
                </div>
            </div>
            <script src="http://www.google-analytics.com/ga.js" type="text/javascript"></script>
            <script type="text/javascript">
                $.ajaxSetup( {
                    dataType: 'xml'
                } );
                _aXSLT.defaultStylesheet = 'global.xsl';
                if ( window.ActiveXObject ) {
                    _aXSLT.ROOT_PATH = '*[1]';
                }

                $( function() { $( '.time' ).live( 'load', function () {
                    Kamibu.TimeFollow( this );
                } ).load(); } );

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
                    Routing[ MasterTemplate ].Init();
                }
                Notifications.Check();
                Presence.Init();
                Chat.Init();
                var pageTracker = _gat._getTracker("UA-1065489-1");
                pageTracker._trackPageview();
            </script>
        </body>
    </html>
</xsl:template> 
