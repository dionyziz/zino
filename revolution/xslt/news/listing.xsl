<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomout.xsl" />
    <xsl:include href="../author.xsl" />
    <xsl:template match="feed">
        <div class="newsfeed">
            <xsl:for-each select="entry">
                <li>
                    <xsl:if test="@type = 'poll' ">
                        <a>
                            <xsl:attribute name="href">
                                poll/<xsl:value-of select="@id" />
                            </xsl:attribute>
                            <span class="title">
                                <xsl:value-of select="question[1]" />
                            </span>
                        </a>
                    </xsl:if>
                    <xsl:if test="@type = 'journal' ">
                        <a>
                            <xsl:attribute name="href">
                                journal/<xsl:value-of select="@id" />
                            </xsl:attribute>
                            <span class="title">
                                <xsl:value-of select="title[1]" />
                            </span>
                        </a>
                    </xsl:if>
                    <span>
                     από την/τον 
                        <xsl:apply-templates select="author" />
                    </span>
                </li>
            </xsl:for-each>
        </div>
    </xsl:template>
</xsl:stylesheet>