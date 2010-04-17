<?xml version="1.0"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:html="http://www.w3.org/1999/xhtml"
xmlns:wadl="http://wadl.dev.java.net/2009/02">

<xsl:variable name="doctitle">
	<xsl:choose>
		<xsl:when test="/wadl:application/wadl:doc[@title]">
			<xsl:value-of select="/wadl:application/wadl:doc/@title" />
		</xsl:when>
		<xsl:otherwise>Application</xsl:otherwise>
	</xsl:choose> Documentation
</xsl:variable>

<xsl:template match="/">
    <html>
        <head>
            <title><xsl:value-of select="$doctitle" /></title>
			<style>
			body {
				font-family: arial;
				font-size: 10pt;
			}
			ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
			}
			ul.toc li.resource a {
				text-decoration: underline;
			}
			ul.toc ul.methods {
				margin-left: 30px;
				list-style-type: square;
			}
			div.method {
				background-color: #ccccee;
				margin-bottom: 2px;
				padding: 10px;
			}
			a.resource {
				text-decoration: none;
			}
			
			a.resource:hover {
				text-decoration: underline;
			}
			table.parameters th {
				font-size: 10pt;
				border-bottom: 1px solid black;
				text-align: left;
				padding: 2px;
			}
			table.parameters td {
				padding: 2px;
				font-size: 10pt;
			}
			</style>
			<script type="text/javascript">
			var activeContent = 'index';
			var d = document;
			window.onhashchange = function() {
				showContent( window.location.hash.substring( 1 ) );
			}
			function showContent( id ) {
				d.getElementById( activeContent ).style.display = 'none';
				activeContent = id;
				d.getElementById( activeContent ).style.display = 'block';
			}
			</script>
        </head>
        <body onload="showContent( window.location.hash.length > 0 ? window.location.hash.substring( 1 ) : 'index' );">
			<h1><xsl:value-of select="$doctitle" /></h1>
            <div style="float: left; "><xsl:apply-templates select="/wadl:application/wadl:resources" mode="toc" /></div>
			<div style="margin-left: 140px; border-left: 1px solid #ccc; padding-left: 20px;">
				<div id="index" style="display: none;">
					<p>Welcome fellow programmer!</p>
					<xsl:apply-templates select="/wadl:application/wadl:doc/html:*" mode="html" />
					<p>Below is a list of all the resources available, and their methods. Click on a resource from the list or from the
					navigation on the sidebar to view more details about the resource, its methods, and their request parameters and response format.</p>
					<xsl:apply-templates select="/wadl:application/wadl:resources/wadl:resource" mode="list" />
				</div>
				<xsl:apply-templates select="/wadl:application/wadl:resources/wadl:resource" mode="view" />
			</div>
        </body>
    </html>
</xsl:template>

<xsl:template match="wadl:doc">
	<xsl:choose>
		<xsl:when test="html:*">
			<xsl:apply-templates select="html:*" mode="html" />
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="." />
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="html:*" mode="html">
<xsl:copy-of select="." />
</xsl:template>

<xsl:template match="wadl:resources" mode="toc">
	<ul class="toc">
		<b>Contents</b><br />
		<a href="#index">Index</a><br />
		<xsl:for-each select="wadl:resource">
			<li class="resource">
			<a href="#resource_{@path}" class="title"><xsl:value-of select="@path" /></a>
			<ul class="methods">
				<xsl:for-each select="wadl:method">
					<li><xsl:value-of select="wadl:doc/@title" /></li>
				</xsl:for-each>
			</ul></li>
		</xsl:for-each>
	</ul>
</xsl:template>

<xsl:template match="wadl:resource" mode="list">
	<h2><a href="#resource_{@path}" class="resource"><xsl:value-of select="@path" /></a></h2>
	<xsl:apply-templates select="wadl:method" mode="list" />
</xsl:template>

<xsl:template match="wadl:method" mode="list">
	<xsl:variable name="uri"><xsl:value-of select="//wadl:resources[1]/@base" /><xsl:value-of select="../@path" /></xsl:variable>
	<div class="method">
	<h3 style="padding-top: 0px; margin-top: 0px; margin-bottom: 4px;"><xsl:value-of select="wadl:doc/@title" /></h3>
	Request: <xsl:value-of select="@name" />&#160;<a href="{$uri}"><xsl:value-of select="$uri" /></a>
	<xsl:if test="wadl:request/wadl:param[@style='query']">
		<xsl:for-each select="wadl:request/wadl:param[@style='query']">
			<xsl:choose>
				<xsl:when test="preceding-sibling::wadl:param[@style='query']">&amp;</xsl:when>
				<xsl:otherwise>?</xsl:otherwise>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="@required='true'"><b><xsl:value-of select="@name" /></b></xsl:when>
				<xsl:otherwise><xsl:value-of select="@name" /></xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
	</xsl:if>
	</div>
</xsl:template>

<xsl:template match="wadl:resource" mode="view">
	<div id="resource_{@path}" style="display: none;">
	<h2><xsl:value-of select="@path" /></h2>
	<div style="margin-bottom: 15px;">
	<xsl:apply-templates select="wadl:doc" />
	</div>
	<xsl:apply-templates select="wadl:method" mode="view" />
	</div>
</xsl:template>

<xsl:template match="wadl:method" mode="view">
	<xsl:variable name="uri"><xsl:value-of select="//wadl:resources[1]/@base" /><xsl:value-of select="../@path" /></xsl:variable>
	<div class="method">
	<h3 style="padding-top: 0px; margin-top: 0px; margin-bottom: 4px;"><xsl:value-of select="wadl:doc/@title" /></h3>
	<div style="margin-top: 10px; margin-bottom: 10px;">
		<xsl:apply-templates select="wadl:doc" />
	</div>
	Request: <xsl:value-of select="@name" />&#160;<a href="{$uri}"><xsl:value-of select="$uri" /></a>
	<xsl:if test="wadl:request/wadl:param[@style='query']">
		<xsl:for-each select="wadl:request/wadl:param[@style='query']">
			<xsl:choose>
				<xsl:when test="preceding-sibling::wadl:param[@style='query']">&amp;</xsl:when>
				<xsl:otherwise>?</xsl:otherwise>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="@required='true'"><b><xsl:value-of select="@name" /></b></xsl:when>
				<xsl:otherwise><xsl:value-of select="@name" /></xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
	</xsl:if>
	<br />
	<xsl:if test="wadl:request/wadl:param">
		<h4 style="margin-bottom: 0px;">Parameters:</h4>
		<table class="parameters">
			<tr>
				<th>Name</th>
				<th>Type</th>
				<th>Documentation</th>
			</tr>
			<xsl:for-each select="wadl:request/wadl:param">
				<tr>
					<td><xsl:value-of select="@name" /></td>
					<td><xsl:value-of select="@type" /></td>
					<td><xsl:value-of select="wadl:doc" /></td>
				</tr>
			</xsl:for-each>
		</table>
	</xsl:if>
	</div>
</xsl:template>

</xsl:stylesheet>
