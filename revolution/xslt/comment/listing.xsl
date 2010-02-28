<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="comment">
        <div class="thread">
            <div>
                <xsl:attribute name="class">message<xsl:if test="/social/@for = author[1]/name[1]"> mine</xsl:if></xsl:attribute>
                <div class="author">
                    <xsl:if test="author[1]/avatar[1]">
                        <img class="avatar">
                            <xsl:attribute name="src">
                                <xsl:value-of select="author[1]/avatar[1]/media[1]/@url" />
                            </xsl:attribute>
                        </img>
                    </xsl:if>
                    <div class="details">
                        <div class="username"><xsl:value-of select="author[1]/name[1]" /></div>
                        <div class="time">
                            <xsl:value-of select="published" />
                        </div>
                    </div>
                </div>
                <div class="text">
                    <xsl:apply-templates select="text" />
                </div>
                <div class="eof"></div>
            </div>
            <xsl:apply-templates select="comment" />
        </div>
    </xsl:template>
    <xsl:template match="a|img|span">
        <xsl:copy-of select="." />
    </xsl:template>
</xsl:stylesheet>
