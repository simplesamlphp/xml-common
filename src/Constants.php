<?php

declare(strict_types=1);

namespace SimpleSAML\XML;

/**
 * Various XML constants.
 *
 * @package simplesamlphp/xml-common
 */
class Constants
{
    /**
     * The namespace fox XML.
     */
    public const NS_XS = 'http://www.w3.org/XML/1998/namespace';

    /**
     * The namespace fox XML schema.
     */
    public const NS_XS = 'http://www.w3.org/2001/XMLSchema';

    /**
     * The namespace for XML schema instance.
     */
    public const NS_XSI = 'http://www.w3.org/2001/XMLSchema-instance';

    /**
     * The namespace for the ECP protocol.
     */
    public const NS_ECP = 'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp';

    /**
     * The namespace for the SAML 2 HoK Web Browser SSO Profile.
     */
    public const NS_HOK = 'urn:oasis:names:tc:SAML:2.0:profiles:holder-of-key:SSO:browser';

    /**
     * The namespace for the SAML 2 metadata.
     */
    public const NS_MD = 'urn:oasis:names:tc:SAML:2.0:metadata';

    /**
     * The namespace for the SAML 2 assertions.
     */
    public const NS_SAML = 'urn:oasis:names:tc:SAML:2.0:assertion';

    /**
     * The namespace for the SAML 2 protocol.
     */
    public const NS_SAMLP = 'urn:oasis:names:tc:SAML:2.0:protocol';

    /**
     * The namespace for the SOAP protocol.
     */
    public const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';

    /**
     * The namespace for the XML encryption schema.
     */
    public const NS_XENC = 'http://www.w3.org/2001/04/xmlenc#';

    /**
     * The namespace for the XML signing schema.
     */
    public const NS_XDSIG = 'http://www.w3.org/2000/09/xmldsig#';
}
