<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="utf-8" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
    <xsl:template match="/social">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
            <head>
                <title>Zino</title>
                <base>
                    <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
                </base>
                <link type="text/css" href="css/frontpage.css" rel="stylesheet" />
                <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
            </head>
            <body>
                <div class="highlight-content">
                    <xsl:apply-templates />
                </div>
                <script type="text/javascript">
                    var User = '<xsl:value-of select="@for" />';
                    var Now = '<xsl:value-of select="@generated" />';
                    var Which = '<xsl:value-of select="/social/entry[1]/@id" />';
                </script>
                <script type="text/javascript" src="http://www.zino.gr/js/date.js"></script>
                <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
                <script type="text/javascript" src="js/experiment.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
