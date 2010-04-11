<xsl:variable name="mastertemplate">
    <xsl:value-of select="/social/@template" />
</xsl:variable>

<xsl:template match="/" priority="1">
    <xsl:choose>
        <xsl:when test="$mastertemplate = 'session.view' "><xsl:apply-templates /></xsl:when>
        <xsl:otherwise>
            <xsl:call-template name="html" />
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<!--eat all other output-->
<xsl:template match="*|text()" priority="-1"/>

<xsl:template name="html">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
        <head>
            <base><xsl:attribute name="href"><xsl:value-of select="/social[1]/@generator" />/</xsl:attribute></base>
            <title>Zino</title>
            <link type="text/css" href="global.css" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
            <script type="text/javascript">
                var OnLoad = function () {};
                var Startup = function ( method ) {
                    var f = OnLoad;
                    OnLoad = function () {
                        f();
                        method();
                    };
                };
            </script>
            <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
            <script type="text/javascript" src="http://www.zino.gr/js/date.js"></script>
            <script type="text/javascript" src="http://chorvus.com/axslt/axslt.js"></script>
            <script type="text/javascript">
                var User = '<xsl:value-of select="/social/@for" />';
                var Now = '<xsl:value-of select="/social/@generated" />';
                var Which = '<xsl:value-of select="/social/entry[1]/@id" />';
            </script>
            <script type="text/javascript" src="global.js"></script>
        </head>
        <body>
            <xsl:apply-templates />
            <script type="text/javascript">
                OnLoad();
            </script>
        </body>
    </html>
</xsl:template>
