<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomin.xsl" />
    <xsl:include href="../comment/listing.xsl" />
    <xsl:include href="../author.xsl" />
    <xsl:template match="entry">
        <a class="xbutton" href="photos">&#171;</a>
        <div class="contentitem">
            <xsl:attribute name="id">photo_<xsl:value-of select="/social/entry/@id" /></xsl:attribute>
            <div class="details">
                <ul>
                    <li>
                        <xsl:apply-templates select="author" />
                    </li>
                    <li><div class="time"><xsl:value-of select="published" /></div></li>
                    <xsl:if test="favourites[1]/@count &gt; 0">
                        <li class="stat numfavourites">&#9829; <span><xsl:value-of select="favourites[1]/@count" /></span></li>
                    </xsl:if>
                    <xsl:if test="discussion[1]/@count &gt; 0">
                        <li class="stat numcomments"><span><xsl:value-of select="discussion[1]/@count" /></span></li>
                    </xsl:if>
                </ul>
            </div>
            <img class="maincontent">
                <xsl:attribute name="src">
                    <xsl:value-of select="media[1]/@url" />
                </xsl:attribute>
                <xsl:attribute name="width">
                    <xsl:value-of select="media[1]/@width" />
                </xsl:attribute>
                <xsl:attribute name="height">
                    <xsl:value-of select="media[1]/@height" />
                </xsl:attribute>
            </img>
            <span class="title">
                <xsl:value-of select="title[1]" />
            </span>
            <div class="note">
                <xsl:for-each select="favourites/user">
                    <div class="love">&#9829; <span class="username"><xsl:value-of select="name[1]" /> </span> </div>
                </xsl:for-each>
                <a class="love button" href="" style="display:none"><strong>&#9829;</strong> Το αγαπώ!</a>
            </div>
        </div>
        <xsl:apply-templates select="discussion" />
    </xsl:template>
    <xsl:template match="discussion">
        <div class="discussion">
            <xsl:if test="/social/@for">
                <div class="note">
                    <a href="" class="talk button">Ξεκίνα μία συζήτηση</a>
                </div>
            </xsl:if>
            <xsl:apply-templates select="comment" />
        </div>
    </xsl:template>
</xsl:stylesheet>
