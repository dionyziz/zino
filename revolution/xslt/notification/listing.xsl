<xsl:template match="/social[@resource='notification' and @method='listing']">
    <div id="notifications" class="panel bottom novideo">
        <div class="background"></div>
        <div class="vbutton"></div>
        <h3>
            <xsl:text>Ενημερώσεις (</xsl:text>
            <xsl:value-of select="stream/@count" />
            <xsl:text>)</xsl:text>
        </h3>
        <xsl:apply-templates select="stream/*" mode="list"/>
    </div> 
</xsl:template>

<xsl:template match="/social[@resource='notification' and @method='listing']/stream/*" mode="list">
    <div class="box">
        <xsl:attribute name="id">
            <xsl:choose>
                <xsl:when test="name">
                    <xsl:text>user_</xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="@type"/>
                    <xsl:text>_</xsl:text>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="@id"/>
        </xsl:attribute>
        <div>
            <img>
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test=".//media">
                            <xsl:value-of select=".//media/@url" />
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select=".//name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4>
                <xsl:value-of select=".//name" />
            </h4>
            <xsl:choose>
                <xsl:when test="name">
                    <div class="friend">
                        <xsl:choose>
                            <xsl:when test="gender='f'">
                                <xsl:text>φίλη</xsl:text>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>φίλος</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </div>
                </xsl:when>
                <xsl:when test="favourites">
                    <div class="background"></div>
                    <div class="love">❤</div>
                </xsl:when>
                <xsl:when test="discussion/comment/comment">
                    <div class="background"></div>
                    <div class="text">
                        <xsl:value-of select="discussion/comment/comment/text" />
                    </div>
                </xsl:when>
                <xsl:otherwise>
                    <div class="background"></div>
                    <div class="text">
                        <xsl:value-of select="discussion/comment/text" />
                    </div>
                </xsl:otherwise>
            </xsl:choose>
        </div>
    </div>
</xsl:template>

<xsl:template match="/social[@resource='notification' and @method='listing']/stream/entry" mode="view">
<div class="content">
    <xsl:choose>
        <xsl:when test="@type='photo'">
            <div class="tips">Πάτα για μεγιστοποίηση</div>
                <div class="contentitem">
                    <xsl:attribute name="id">
                        <xsl:text>photo_</xsl:text>
                        <xsl:value-of select="@id" />
                    </xsl:attribute>
                    <div class="image">
                        <img class="maincontent">
                            <xsl:attribute name="src">
                            </xsl:attribute>
                        </img>
                    </div>
                    <div class="title">
                        
                    </div>
                    <div class="note">

                    </div>
                </div>
            </xsl:when>
            <xsl:otherwise>
            </xsl:otherwise>
        </xsl:choose>
    </div>
    <div class="details">
        
    </div>
</xsl:template>
