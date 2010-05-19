<xsl:template match="/social[@resource='photo' and @method='listing']">
    <xsl:call-template name="zoomout" />
</xsl:template>

<xsl:template match="/social[@resource='photo' and @method='listing']//stream">
    <div class="photostream">
        <ul>
                <li>
                    <form method="post" action="photo/create">
                        <div>
                            <label class="cabinet">
                                <input type="file" name="uploadimage" class="file" />
                            </label>
                        </div>
                    </form>
                </li>
            <xsl:for-each select="entry">
                <li>
                    <a>
                        <xsl:attribute name="href">photos/<xsl:value-of select="@id" /></xsl:attribute>
                        <img>
                            <xsl:attribute name="src">
                                <xsl:value-of select="media[1]/@url" />
                            </xsl:attribute>
                        </img>
                        <xsl:if test="discussion[1]/@count &gt; 0">
                            <span class="countbubble">
                                <xsl:if test="discussion[1]/@count &gt; 99">
                                    &#8734;
                                </xsl:if>
                                <xsl:if test="discussion[1]/@count &lt; 100">
                                    <xsl:value-of select="discussion[1]/@count" />
                                </xsl:if>
                            </span>
                        </xsl:if>
                    </a>
                </li>
            </xsl:for-each>
        </ul>
    </div>
    <script type="text/javascript">
        // SI.Files.stylizeAll();
        Startup( function () {
            PhotoListing.Init();
        } );
    </script>
</xsl:template> 
