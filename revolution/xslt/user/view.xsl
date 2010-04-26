<xsl:template match="/social[@resource='user'][@method='view']">
    <xsl:for-each select="user">
        <a class="xbutton" href="photos">&#171;</a>
        <h2><xsl:value-of select="name[1]" /></h2>
        <xsl:if test="slogan[1]">
            <h3><xsl:value-of select="slogan[1]" /></h3>
        </xsl:if>
        <a class="avatar">
            <xsl:attribute name="href">photo/<xsl:value-of select="avatar[1]/@id" /></xsl:attribute>
            <img class="avatar">
                <xsl:attribute name="src">
                    <xsl:value-of select="avatar[1]/media[1]/@url" />
                </xsl:attribute>
            </img>
        </a>
        <xsl:apply-templates select="discussion" />
    </xsl:for-each>
</xsl:template>
