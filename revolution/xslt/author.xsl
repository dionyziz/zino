<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="author">
        <xsl:if test="avatar[1]">
            <div class="author">
                <a>
                    <xsl:attribute name="href">http://<xsl:value-of select="subdomain[1]" />.zino.gr/</xsl:attribute>
                    <img class="avatar">
                        <xsl:attribute name="src">
                            <xsl:value-of select="avatar[1]/media[1]/@url" />
                        </xsl:attribute>
                    </img>
                </a>
            </div>
        </xsl:if>
        <span class="username"><a><xsl:attribute name="href">http://<xsl:value-of select="subdomain[1]" />.zino.gr/</xsl:attribute><xsl:value-of select="name[1]" /></a></span>
    </xsl:template>
</xsl:stylesheet>
