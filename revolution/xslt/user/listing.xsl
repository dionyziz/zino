<xsl:template match="crowd">
    <ul>
        <xsl:for-each select="user">
            <li>
                <xsl:value-of select="name[0]" />
            </li>
        </xsl:for-each>
    </ul>
</xsl:template>
