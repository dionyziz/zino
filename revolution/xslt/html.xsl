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
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
            <script type="text/javascript" src="http://www.zino.gr/js/date.js"></script>
            <script type="text/javascript" src="global.js"></script>
            <script type="text/javascript">
                <xsl:if test="/social/@for">
                    var User = '<xsl:value-of select="/social/@for" />';
                </xsl:if>
                var Now = '<xsl:value-of select="/social/@generated" />';
                var Which = '<xsl:value-of select="/social/entry[1]/@id" />';
                
                var XMLData = {
                    author: '<xsl:value-of select="/social/entry[1]/author[1]/name[1]" />'
                }
            </script>
        </head>
        <body onload="Comet.OnBodyLoaded()">
            <div id="world">
                <div class="bar">
                    <span>▼</span>
                    <img src="http://static.zino.gr/revolution/bubble-small-trans2.png" />
                     
                    <ul>
                        <li>
                            <xsl:if test="/social/photos">
                                <xsl:attribute name="class">selected</xsl:attribute>
                            </xsl:if>
                            <a style="background-image: url('http://zino.gr:500/dionyziz/images/images.png');" href="">Εικόνες</a>
                        </li>
                        <li>
                            <xsl:if test="/social/news">
                                <xsl:attribute name="class">selected</xsl:attribute>
                            </xsl:if>
                            <a style="background-image: url('http://zino.gr:500/dionyziz/images/world.png');" href="news">Νέα</a>
                        </li>
                        <li>
                            <xsl:if test="/social/@for">
                                <a id="logoutbutton" style="background-image: url('http://zino.gr:500/dionyziz/images/user.png');">
                                    <xsl:attribute name="href">
                                        users/<xsl:value-of select="/social/@for" />
                                    </xsl:attribute>
                                    Προφίλ
                                </a>
                            </xsl:if>
                            <xsl:if test="not(/social/@for)">
                                <a style="background-image: url('http://zino.gr:500/dionyziz/images/user.png');" href="login" id="loginbutton"><img src="images/user.png" alt="Είσοδος" title="Είσοδος" /><span>Είσοδος</span></a>
                            </xsl:if>
                        </li>
                        <li style="float: right; padding-right: 0px;margin-right: 7px;">
                            <a href="" id="chatbutton" style="background-image: url('http://zino.gr:500/dionyziz/images/comments.png');">Chat</a>
                        </li>
                    </ul>
                </div>
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
                }[ '<xsl:value-of select="$mastertemplate" />' ].Init();
                Presence.Init();
                Chat.Init();
            </script>
        </body>
    </html>
</xsl:template> 
