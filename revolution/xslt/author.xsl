<xsl:template match="author">
    <xsl:if test="avatar[1]">
        <a class="avatar">
            <xsl:attribute name="href">http://<xsl:value-of select="subdomain[1]" />.zino.gr/</xsl:attribute>
            <img class="avatar">
                <xsl:attribute name="src">
                    <xsl:value-of select="avatar[1]/media[1]/@url" />
                </xsl:attribute>
            </img>
        </a>
    </xsl:if>
    <a class="username"><xsl:attribute name="href">http://<xsl:value-of select="subdomain[1]" />.zino.gr/</xsl:attribute><xsl:value-of select="name[1]" /></a>
</xsl:template>