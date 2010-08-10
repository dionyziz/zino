<xsl:template match="/social[@resource='notification' and @method='listing']">
    <div id="notifications" class="panel bottom novideo">
        <div class="background"></div>
        <div class="vbutton"></div>
        <h3>Ενημερώσεις (<xsl:value-of select="notifications/@count" />)</h3>
        <xsl:apply-templates select="notifications/notification" mode="list"/>
    </div>
    <div id="instantbox">
        <ul class="tips">
            <li>Enter = <strong>Αποθήκευση μηνύματος</strong></li>
            <li>Escape = <strong>Αγνόηση</strong></li>
            <li>Shift + Esc = <strong>Θα το δω μετά</strong></li>
        </ul>
        <div class="content" />
        <xsl:apply-templates select="notifications/notification" mode="view"/>
    </div>
</xsl:template>

<xsl:template match="notification[@type='favourite']" mode="list">
    <div class="box">
        <xsl:attribute name="id">notification_<xsl:value-of select="@id" /></xsl:attribute>
        <div>
            <img>
                <xsl:choose>
                    <xsl:when test="*/favourites/user/avatar">
                        <xsl:attribute name="src">
                            <xsl:value-of select="*/favourites/user/avatar/media/@url" />
                        </xsl:attribute>
                    </xsl:when>
                    <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                </xsl:choose>
                <xsl:attribute name="alt">
                    <xsl:value-of select="*/favourites/user/name" />
                </xsl:attribute>
                <xsl:attribute name="title">
                    <xsl:value-of select="*/favourites/user/name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4><xsl:value-of select="*/favourites/user/name" /></h4>
            <div class="background"></div>
            <div class="love">❤</div>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='friend']" mode="list">
    <div class="box">
        <xsl:attribute name="id">notification_<xsl:value-of select="@id" /></xsl:attribute>
        <div>
            <img>
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test="user/avatar">
                            <xsl:value-of select="user/avatar/media/@url" />
                        </xsl:when>
                        <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select="user/name" />
                </xsl:attribute>
                <xsl:attribute name="title">
                    <xsl:value-of select="user/name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4><xsl:value-of select="*/favourites/user/name" /></h4>
            <div class="friend">
                <xsl:choose>
                    <xsl:when test="user/gender='f'">
                        φίλη
                    </xsl:when>
                    <xsl:otherwise>
                        φίλος
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='comment']" mode="list">
    <div class="box">
        <xsl:attribute name="id">notification_<xsl:value-of select="@id" /></xsl:attribute>
        <div>
            <img>
                <xsl:attribute name="src">
                    <xsl:choose>
                        <xsl:when test="*/discussion/comment/comment/author">
                            <xsl:choose>
                                <xsl:when test="*/discussion/comment/comment/author/avatar">
                                    <xsl:value-of select="*/discussion/comment/comment/author/avatar/media/@url" />
                                </xsl:when>
                                <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                            </xsl:choose>
                        </xsl:when>
                        <xsl:when test="*/discussion/comment/author/avatar">
                            <xsl:value-of select="*/discussion/comment/author/avatar/media/@url" />
                        </xsl:when>
                        <xsl:otherwise>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:attribute name="alt">
                    <xsl:value-of select=".//name" />
                </xsl:attribute>
            </img>
        </div>
        <div class="details">
            <h4>
                <xsl:choose>
                    <xsl:when test="*/discussion/comment/comment/author/name">
                        <xsl:value-of select="*/discussion/comment/comment/author/name" />
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select=".//name" />
                    </xsl:otherwise>
                </xsl:choose>
            </h4>
            <xsl:choose>
                <xsl:when test="*/discussion/comment/comment">
                    <div class="background"></div>
                    <div class="text">
                        <xsl:value-of select="*/discussion/comment/comment/text" />
                    </div>
                </xsl:when>
                <xsl:otherwise>
                    <div class="background"></div>
                    <div class="text">
                        <xsl:value-of select="*/discussion/comment/text" />
                    </div>
                </xsl:otherwise>
            </xsl:choose>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='comment']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@id"/></xsl:attribute>
        <div class="details">
            <xsl:choose>
                <xsl:when test="*/discussion/comment/comment">
                    <p><strong>
                        <xsl:choose>
                            <xsl:when test="*/discussion/comment/comment/author/gender='f'">
                                <xsl:text>Η </xsl:text>
                                <xsl:value-of select="*/discussion/comment/comment/author/name" />
                                <xsl:text> απάντησε στο σχόλιό σου</xsl:text>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>O </xsl:text>
                                <xsl:value-of select="*/discussion/comment/comment/author/name" />
                                <xsl:text> απάντησε στο σχόλιό σου</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </strong></p>
                    <xsl:apply-templates select="*/discussion/comment"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:apply-templates select="*/discussion/comment"/>
                </xsl:otherwise>
            </xsl:choose>
            <p class="note">
                <div class="thread new" style="display: block;">
                    <div class="message mine new">
                        <div><textarea></textarea></div>
                    </div>
                </div>
                Ή πάτησε ESC αν δεν θέλεις να αφήσεις σχόλιο
            </p>
        </div>
    </div>
</xsl:template>

<xsl:template match="notification[@type='friend']" mode="view">
    <div class="instantbox">
        <xsl:attribute name="id">ib_<xsl:value-of select="@id"/></xsl:attribute>
        <div class="businesscard">
            <div class="avatar">
                <a>
                    <xsl:attribute name="href">
                        <xsl:text>users/</xsl:text>
                        <xsl:value-of select="*/favourites/user/name" />
                    </xsl:attribute>
                    <img>
                        <xsl:attribute name="src">
                            <xsl:choose>
                                <xsl:when test="*/favourites/user/avatar/media">
                                    <xsl:value-of select="*/favourites/user/avatar/media/@url" />
                                </xsl:when>
                                <xsl:otherwise>
                                   <xsl:text>http://static.zino.gr/phoenix/anonymous100.jpg</xsl:text>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:attribute>
                        <xsl:attribute name="alt">
                            <xsl:value-of select="*/favourites/user/name"/>
                        </xsl:attribute>
                    </img>
                </a>
            </div>
            <div class="username">
                <a>
                    <xsl:attribute name="href">
                        <xsl:text>users/</xsl:text>
                        <xsl:value-of select="*/favourites/user/name"/>
                    </xsl:attribute>
                    <xsl:value-of select="*/favourites/user/name" />
                </a>
            </div>
            <ul class="details">
                <xsl:if test="*/favourites/user/gender!='-'">
                    <li>
                        <xsl:choose>
                            <xsl:when test="*/favourites/user/gender='f'">
                                Κορίτσι
                            </xsl:when>
                            <xsl:otherwise>
                                Αγόρι
                            </xsl:otherwise>
                        </xsl:choose>
                    </li>
                </xsl:if>
                <xsl:if test="*/favourites/user/age">
                    <li>
                        <xsl:value-of select="*/favourites/user/age" />
                    </li>
                </xsl:if>
                <xsl:if test="*/favourites/user/location">
                    <li>
                        <xsl:value-of select="*/favourites/user/location" />
                    </li>
                </xsl:if>
            </ul>
        </div>
    </div>
</xsl:template>
