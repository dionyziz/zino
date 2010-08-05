<xsl:template match="/social[@resource='album' and @method='listing']">
    <ol id="albumlist">
        <xsl:apply-templates />
    </ol>
</xsl:template>

<xsl:template match="/social[@resource='album' and @method='listing']/albums">
    <xsl:for-each select="album">
        <li>
            <a>
                <xsl:attribute name="href">
                    <xsl:text>albums/</xsl:text>
                    <xsl:value-of select="@id" />
                </xsl:attribute>
                <img>
                    <xsl:attribute name="src">
                        <xsl:if test="photos/photo/@main='yes'">
                            <xsl:value-of select="photos/photo[@main='yes']/media/@url" />
                        </xsl:if>
                    </xsl:attribute>
                </img>
                <div class="deletebutton">×</div>
            </a>
            <p>
                <xsl:value-of select="name" />
            </p>
        </li>
    </xsl:for-each>
</xsl:template>
