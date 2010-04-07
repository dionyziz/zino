<xsl:template name="zoomin">
    <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
    <div class="highlight-content">
        <xsl:apply-templates />
    </div>
    <script type="text/javascript">
        var User = '<xsl:value-of select="@for" />';
        var Now = '<xsl:value-of select="@generated" />';
        var Which = '<xsl:value-of select="/social/entry[1]/@id" />';
    </script>
</xsl:template>
