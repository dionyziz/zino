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
                                photos/<xsl:value-of select="@id" />
                            </xsl:attribute>
                            <img width="150" height="150">
                                <xsl:attribute name="src">
                                    <xsl:value-of select="media[1]/@url" />
                                </xsl:attribute>
                            </img>
                            <xsl:if test="discussion[1]/@count &gt; 0">
                                <span class="countbubble">
                                    <xsl:if test="discussion[1]/@count &gt; 99">
                                        &#8734;
                                    </xsl:if>
                                    <xsl:if test="discussion[1]/@count &lt; 100">
                                        <xsl:value-of select="discussion[1]/@count" />
                                    </xsl:if>
                                </span>
                            </xsl:if>
                        </a>
                    </li> <xsl:text> </xsl:text>
                </xsl:for-each>
            </ul>
        </div>
    </xsl:template>
</xsl:stylesheet>
