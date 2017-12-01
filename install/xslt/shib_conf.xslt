<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="text" encoding="UTF-8" indent="no" />
    <xsl:strip-space elements="*" />

    <xsl:template match="/" mode="">
        <xsl:apply-templates />
    </xsl:template>

    <xsl:template match="*[local-name()='ApplicationDefaults']">entityId: "<xsl:value-of select="@entityID" />"
REMOTE_USER: "<xsl:value-of select="@REMOTE_USER" />"
<xsl:apply-templates />
</xsl:template>

    <xsl:template match="*[local-name()='Sessions']">
        <xsl:apply-templates />
    </xsl:template>

    <xsl:template match="*[local-name()='SSO']">SSO.discoveryProtocol: "<xsl:value-of select="@discoveryProtocol" />"
SSO.discoveryURL: "<xsl:value-of select="@discoveryURL" />"
</xsl:template>

    <xsl:template match="*[local-name()='Handler' and @type='Status']">StatusHandler.Location.acl: "<xsl:value-of select="@acl" />"
</xsl:template>

    <xsl:template match="*[local-name()='Errors']">Errors.supportContact: "<xsl:value-of select="@supportContact" />"
</xsl:template>

    <xsl:template match="*[local-name()='MetadataProvider']">MetadataProvider.url: "<xsl:value-of select="@url" />"
<xsl:apply-templates />
</xsl:template>

    <xsl:template match="*[local-name()='MetadataFilter']">MetadataSignatureFilter.certificate: "<xsl:value-of select="@certificate" />"
</xsl:template>

    <xsl:template match="text()" />
</xsl:stylesheet>

