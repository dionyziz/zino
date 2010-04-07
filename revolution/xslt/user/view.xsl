<xsl:template match="/social[@template='user.view']">
    <a class="xbutton" href="photos">&#171;</a>
    User profile!
    <xsl:apply-templates select="discussion" />
</xsl:template>