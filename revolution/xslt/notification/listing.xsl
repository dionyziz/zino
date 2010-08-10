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
    <div id="instantbox">
        <ul class="tips">
            <li>Enter = <strong>Αποθήκευση μηνύματος</strong></li>
            <li>Escape = <strong>Αγνόηση</strong></li>
            <li>Shift + Esc = <strong>Θα το δω μετά</strong></li>
        </ul>
        <div class="content" />
        <xsl:apply-templates select="stream/*" mode="view"/>
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

<xsl:template match="/social[@resource='notification' and @method='listing']/stream/*" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">
            <xsl:text>ib_</xsl:text>
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
        <div class="details">
            <xsl:choose>
                <xsl:when test="discussion/comment/comment">
                    <p><strong>
                        <xsl:choose>
                            <xsl:when test="discussion/comment/comment/author/gender='f'">
                                <xsl:text>Η </xsl:text>
                                <xsl:value-of select="discussion/comment/comment/author/name" />
                                <xsl:text> απάντησε στο σχόλιό σου</xsl:text>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>O </xsl:text>
                                <xsl:value-of select="discussion/comment/comment/author/name" />
                                <xsl:text> απάντησε στο σχόλιό σου</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </strong></p>
                    <xsl:apply-templates select="discussion/comment"/>
                    <p class="note">
                        <div class="thread new" style="display: block;">
                            <div class="message mine new">
                                <div><textarea></textarea></div>
                            </div>
                        </div>
                    </p>
                </xsl:when>
                <xsl:when test="discussion/comment">
                    <xsl:apply-templates select="discussion/comment"/>
                    <p class="note">
                        <div class="thread new" style="display: block">
                            <div class="message mine new">
                                <div><textarea></textarea></div>
                            </div>
                        </div>
                        <xsl:text>Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο</xsl:text>
                    </p>
                </xsl:when>
                <xsl:when test="favourites">
                    <div class="businesscard">
                        <div class="avatar">
                            <a>
                                <xsl:attribute name="href">
                                    <xsl:text>users/</xsl:text>
                                    <xsl:value-of select="favourites/user/name" />
                                </xsl:attribute>
                                <img>
                                    <xsl:attribute name="src">
                                        <xsl:choose>
                                            <xsl:when test="favourites/user/avatar/media">
                                                <xsl:value-of select="favourites/user/avatar/media/@url" />
                                            </xsl:when>
                                            <xsl:otherwise>
                                               <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:attribute>
                                    <xsl:attribute name="alt">
                                        <xsl:value-of select="favourites/user/name"/>
                                    </xsl:attribute>
                                </img>
                            </a>
                        </div>
                        <div class="username">
                            <a>
                                <xsl:attribute name="href">
                                    <xsl:text>users/</xsl:text>
                                    <xsl:value-of select="favourites/user/name"/>
                                </xsl:attribute>
                                <xsl:value-of select="favourites/user/name" />
                            </a>
                        </div>
                        <ul class="details">
                            <xsl:if test="favourites/user/gender!='-'">
                                <li>
                                    <xsl:choose>
                                        <xsl:when test="favourites/user/gender='f'">
                                            <xsl:text>Κορίτσι</xsl:text>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:text>Αγόρι</xsl:text>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </li>
                            </xsl:if>
                            <xsl:if test="favourites/user/age">
                                <li>
                                    <xsl:value-of select="favourites/user/age" />
                                </li>
                            </xsl:if>
                            <xsl:if test="favourites/user/location">
                                <li>
                                    <xsl:value-of select="favourites/user/location" />
                                </li>
                            </xsl:if>
                        </ul>
                    </div>
                </xsl:when>
                <xsl:otherwise>
                </xsl:otherwise>
            </xsl:choose>
        </div>
    </div>
</xsl:template>
