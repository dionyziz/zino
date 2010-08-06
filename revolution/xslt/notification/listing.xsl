<xsl:template match="/social[@resource='notification' and @method='listing']">
    <div id="notifications" class="panel bottom novideo">
        <div class="background"></div>
        <div class="xbutton"></div>
        <h3>
            <xsl:text>Ενημερώσεις (</xsl:text>
            <xsl:value-of select="stream/@count" />
            <xsl:text>)</xsl:text>
        </h3>
        <xsl:apply-templates />
    </div>
</xsl:template>

<xsl:template match="/social[@resource='notification' and @method='listing']/stream/entry">
    <div class="box">
        <div>
            <img>
                
            </img>
        </div>
    </div>
</xsl:template>
