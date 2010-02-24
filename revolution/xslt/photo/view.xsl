<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomin.xsl" />
    <xsl:include href="../comment/listing.xsl" />
    <xsl:template match="entry">
        <div class="portrait">
            <a class="xbutton" href="photos">&#171;</a>
            <img>
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
            <div class="note">
                <xsl:for-each select="favourites/user">
                    <div class="love">&#9829; <div class="username"><xsl:value-of select="name[1]" /> </div> </div>
                </xsl:for-each>
                <a class="love" href="" style="display:none"><strong>&#9829;</strong> Το αγαπώ!</a>
            </div>
        </div>
        <xsl:apply-templates select="discussion" />
    </xsl:template>
    <xsl:template match="discussion">
        <div class="discussion">
            <xsl:apply-templates select="comment" />
        </div>
    </xsl:template>
</xsl:stylesheet>
