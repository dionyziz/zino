<xsl:variable name="mastertemplate">
    <xsl:value-of select="/*[1]/@resource" />.<xsl:value-of select="/*[1]/@method" />
</xsl:variable>
<xsl:variable name="resource">
    <xsl:value-of select="/*[1]/@resource" />
</xsl:variable>
<xsl:variable name="method">
    <xsl:value-of select="/*[1]/@method" />
</xsl:variable>

<xsl:template name="tiny">
    <xsl:apply-templates />
</xsl:template>

<xsl:variable name="user" select="/*[1]/@for" />

<xsl:template match="/" priority="1">
    <xsl:choose>
        <!-- tiny master templates -->
        <xsl:when test="$resource = 'session' "><xsl:apply-templates /></xsl:when>
        
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
            <base><xsl:attribute name="href"><xsl:value-of select="/social[1]/@generator" />/</xsl:attribute></base>
            <title>Zino</title>
            <link type="text/css" href="global.css" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/spriting/sprite1.css" rel="stylesheet" />
            <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
            <script type="text/javascript" src="http://www.zino.gr/js/date.js"></script>
            <script type="text/javascript" src="global.js"></script>
            <script type="text/javascript">
                <xsl:if test="/social/@for">
                    var User = '<xsl:value-of select="/social/@for" />';
                </xsl:if>
                var Now = '<xsl:value-of select="/social/@generated" />';
                
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
                <script type="text/javascript">
                    Notifications.Check();
                </script>
            </div>
            <script type="text/javascript">
                $.ajaxSetup( {
                    dataType: 'xml'
                } );
                _aXSLT.defaultStylesheet = 'global.xsl';
                if ( window.ActiveXObject ) {
                    _aXSLT.ROOT_PATH = '*[1]';
                }

                $( function() { $( '.time' ).each( function () {
                    this.innerHTML = greekDateDiff( dateDiff( this.innerHTML, Now ) );
                    $( this ).addClass( 'processedtime' );
                } ); } );

                var Routing = {
                    'photo.view': PhotoView,
                    'photo.listing': PhotoListing,
                    'news.listing': News,
                    'poll.view': Poll,
                    'journal.view': Journal,
                    'user.view': Profile,
                    'favourite.listing': Favourite,
                    'friendship.listing': Friends,
                };
                var MasterTemplate = '<xsl:value-of select="$mastertemplate" />';
                if ( typeof Routing[ MasterTemplate ] != 'undefined' ) {
                    Routing[ MasterTemplate ].Init();
                }
                Presence.Init();
                Chat.Init();
            </script>
        </body>
    </html>
</xsl:template> 
