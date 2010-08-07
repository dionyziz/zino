<xsl:template match="/social[@resource='album' and @method='view']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="/social[@resource='album' and @method='view']/album">
    <xsl:apply-templates select="photos"/>
</xsl:template>
