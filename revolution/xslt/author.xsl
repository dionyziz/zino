<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="author">
        <a class="username"><xsl:attribute name="href">http://<xsl:value-of select="subdomain[1]" />.zino.gr/</xsl:attribute><xsl:value-of select="name[1]" /></a>
    </xsl:template>
</xsl:stylesheet>
