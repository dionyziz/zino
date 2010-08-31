<xsl:template match="song">
    <div class="player">
        <object>
            <param name="movie" value="http://listen.grooveshark.com/songWidget.swf"></param>
            <param name="wmode" value="opaque"></param>
            <param name="allowScriptAccess" value="always"></param>
            <param name="flashvars">
                <xsl:attribute name="value">hostname=cowbell.grooveshark.com&amp;songID=<xsl:value-of select="@id" />&amp;style=metal</xsl:attribute>
            </param>
            <embed src="http://listen.grooveshark.com/songWidget.swf" type="application/x-shockwave-flash" flashvars="" allowScriptAccess="always" wmode="opaque">
            <xsl:attribute name="flashvars">hostname=cowbell.grooveshark.com&amp;songID=<xsl:value-of select="@id" />&amp;style=metal</xsl:attribute>
            </embed>
        </object>
    </div>
</xsl:template>
