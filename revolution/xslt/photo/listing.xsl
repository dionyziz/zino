<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomout.xsl" />
    <xsl:template match="feed">
        <div class="photofeed">
            <ul>
                <xsl:for-each select="entry">
                    <li>
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="link[1]/@href" />
                            </xsl:attribute>
                            <xsl:if test="discussion[1]/@count &gt; 0">
                                <span class="countbubble">
                                    <xsl:value-of select="discussion[1]/@count" />
                                </span>
                            </xsl:if>
                            <img>
                                <xsl:attribute name="src">
                                    <xsl:value-of select="media[1]/@url" />
                                </xsl:attribute>
                            </img>
                        </a>
                    </li>
                </xsl:for-each>
            </ul>
        </div>
    </xsl:template>
</xsl:stylesheet>
