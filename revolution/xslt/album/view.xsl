<xsl:template match="/social[@resource='album' and @method='view']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="/social[@resource='album' and @method='view']/album">
    <xsl:for-each select="photos" >
        <xsl:call-template name="photolist" />
    </xsl:for-each>
</xsl:template>

