<xsl:template match="/social[@resource='photo' and @method='view']">
    <xsl:apply-templates />
</xsl:template>

<xsl:template match="/social[@resource='photo' and @method='view']//photo">
    <div class="contentitem">
        <xsl:attribute name="id">photo_<xsl:value-of select="/social/photo/@id" /></xsl:attribute>
        <xsl:if test="not( @deleted )">
            <xsl:if test="author">
                <div class="details">
                    <ul>
                        <li>
                            <xsl:apply-templates select="author" />
                        </li>
                        <li><div class="time"><xsl:value-of select="published" /></div></li>
                        <xsl:if test="favourites/@count &gt; 0">
                            <li class="stat numfavourites">&#9829; <span><xsl:value-of select="favourites/@count" /></span></li>
                        </xsl:if>
                        <xsl:if test="discussion/@count &gt; 0">
                            <li class="stat numcomments"><span><xsl:value-of select="discussion/@count" /></span></li>
                        </xsl:if>
                    </ul>
                </div>
                <xsl:call-template name="teaser">
                    <xsl:with-param name="user" select="author" />
                </xsl:call-template>
            </xsl:if>
            <div class="image">
                <xsl:attribute name="style">width: <xsl:value-of select="media/@width" />px;</xsl:attribute>
                <img class="maincontent">
                    <xsl:attribute name="src"><xsl:value-of select="media/@url" /></xsl:attribute>
                    <xsl:attribute name="width"><xsl:value-of select="media/@width" /></xsl:attribute>
                    <xsl:attribute name="height"><xsl:value-of select="media/@height" /></xsl:attribute>
                </img>
                <xsl:if test="/social/@for = author/name">
                    <div class="icon" id="deletebutton" title="Διαγραφή εικόνας">&#215;</div>     
                    <div class="icon left" id="tagbutton"></div>
                </xsl:if>
                <xsl:if test="author/friends">
                    <div class="icon" id="tagbutton"></div>
                </xsl:if>
                <xsl:for-each select="imagetags/imagetag">
                    <div class="tag">
                        <xsl:attribute name="style">
                            left: <xsl:value-of select="left" />px;
                            top: <xsl:value-of select="top" />px;
                            width: <xsl:value-of select="width" />px;
                            height: <xsl:value-of select="height" />px;
                        </xsl:attribute>
                        <xsl:attribute name="id">tag_<xsl:value-of select="@id" /></xsl:attribute>
                        <div class="namecontainer">
                            <xsl:if test="top + height &gt; /social/photo/media/@height - '50'">
                                <xsl:attribute name="class">namecontainer top</xsl:attribute>
                            </xsl:if>
                            <xsl:if test="width &gt; '200'">
                                <xsl:attribute name="class">namecontainer inside</xsl:attribute>
                            </xsl:if>
                            <span class="name">
                                <xsl:attribute name="id">user_<xsl:value-of select="user/@id" /></xsl:attribute>
                                <xsl:value-of select="user" />
                            </span>
                        </div>
                        <div class="imagecontainer">
                            <img>
                                <xsl:attribute name="src"><xsl:value-of select="/social/photo/media/@url" /></xsl:attribute>
                                <xsl:attribute name="style">
                                    left: -<xsl:value-of select="left" />px;
                                    top: -<xsl:value-of select="top" />px;
                                </xsl:attribute>
                            </img>
                        </div>
                    </div>
                </xsl:for-each>
            </div>
        </xsl:if>
        <xsl:if test="/social/@for = author/name">
            <div class="title">
                <input type="text">
                    <xsl:attribute name="value"><xsl:value-of select="title" /></xsl:attribute>
                    <xsl:if test="not(title)">
                        <xsl:attribute name="value">Γράψε τίτλο για τη φωτογραφία</xsl:attribute>
                        <xsl:attribute name="class">empty</xsl:attribute>
                    </xsl:if>
                </input>
                <span class="hidden"><xsl:value-of select="title" /></span>
            </div>
        </xsl:if>
        <xsl:if test="@deleted">Η φωτογραφία έχει διαγραφεί.</xsl:if>
        <xsl:if test="/social/@for != author/name and title">
            <div class="title">
                <span>
                    <xsl:value-of select="title" />
                </span>
            </div>
        </xsl:if>
        <xsl:if test="not(@deleted)">
            <xsl:call-template name="imagetag.list" />
            <xsl:call-template name="favourite.list" />
        </xsl:if>
        <xsl:if test="/social/@for != author/name">
            <a id='report_image' ><span class='mark'>!</span> Αναφορά εικόνας</a>
        </xsl:if>
    </div>
    <div class="navigation" style="display: none;">
        <xsl:if test="/social/photo/author/photos/photo[ @navigation='next' ]">
            <span class="nextid"><xsl:value-of select="/social/photo/author/photos/photo[ @navigation='next' ]/@id" /></span>
        </xsl:if>
        <xsl:if test="/social/photo/author/photos/photo[ @navigation='previous' ]">
            <span class="previousid"><xsl:value-of select="/social/photo/author/photos/photo[ @navigation='previous' ]/@id" /></span>
        </xsl:if>
    </div>
    <xsl:apply-templates select="discussion" />
</xsl:template>

<xsl:template name="imagetag.list">
    <xsl:if test="/social/photo/imagetags">
        <ul class="tagged">
            <xsl:if test="/social/photo/imagetags">
                <li>Σε αυτή τη φωτογραφία: </li>
            </xsl:if>
            <xsl:for-each select="/social/photo/imagetags/imagetag">
                <li class="listtag">
                    <xsl:attribute name="id">listtag_<xsl:value-of select="@id" /></xsl:attribute>
                    <xsl:if test="./@id = ../imagetag[last()]/@id">
                        <xsl:attribute name="class">listtag last</xsl:attribute>
                    </xsl:if>
                    <a>
                        <xsl:attribute name="href">users/<xsl:value-of select="user/name" /></xsl:attribute>
                        <xsl:value-of select="user/name" />
                    </a>
                    <xsl:if test="/social/@for = /social/photo/author/name or /social/@for = user/name"> 
                        <a href="" class="delete"> (Διαγραφή)</a>
                    </xsl:if>
                </li>
            </xsl:for-each>
        </ul>
    </xsl:if>
</xsl:template>
