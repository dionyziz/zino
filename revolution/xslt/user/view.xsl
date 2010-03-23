<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:include href="../zoomin.xsl" />
    <xsl:include href="../comment/listing.xsl" />
    <xsl:template match="user">
        <a class="xbutton" href="photos">&#171;</a>
        User profile!
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

