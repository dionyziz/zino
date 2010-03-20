<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomout.xsl" />
    <xsl:include href="../author.xsl" />
    <xsl:template match="feed">
        <div class="newsfeed">
            <xsl:for-each select="entry">
                <li>
                    <xsl:if test="@type = 'poll' ">
                        <xsl:apply-templates select="author" />
                        <span class="title">
                            <xsl:value-of select="question[1]" />
                        </span>
                    </xsl:if>
                    <xsl:if test="@type = 'journal' ">
                        <xsl:apply-templates select="author" />
                        <span class="title">
                            <xsl:value-of select="title[1]" />
                        </span>
                    </xsl:if>
                </li>
            </xsl:for-each>
        </div>
    </xsl:template>
</xsl:stylesheet>