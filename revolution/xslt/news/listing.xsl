<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomout.xsl" />
    <xsl:include href="../author.xsl" />
    <xsl:template match="feed">
        <div class="feed">
            <ul>
                <xsl:for-each select="entry">
                    <li>
                        <xsl:apply-templates select="author" />
                        <xsl:if test="@type = 'poll' ">
                            <a>
                                <xsl:attribute name="href">polls/<xsl:value-of select="@id" /></xsl:attribute>
                                <span class="title">
                                    <xsl:value-of select="question[1]" />
                                </span>
                            </a>
                        </xsl:if>
                        <xsl:if test="@type = 'journal' ">
                            <a>
                                <xsl:attribute name="href">journals/<xsl:value-of select="@id" /></xsl:attribute>
                                <span class="title">
                                    <xsl:value-of select="title[1]" />
                                </span>
                            </a>
                        </xsl:if>
                    </li>
                </xsl:for-each>
            </ul>
        </div>
    </xsl:template>
</xsl:stylesheet>
