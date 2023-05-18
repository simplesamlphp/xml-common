<?php

declare(strict_types=1);

namespace SimpleSAML\Test\XML;

use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\XML\ExtendableAttributesElement;
use SimpleSAML\Test\XML\ExtendableAttributesTestTrait;
use SimpleSAML\XML\Constants as C;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XML\Attribute;

use function dirname;

/**
 * Class \SimpleSAML\XML\ExtendableAttributesTraitTest
 *
 * @covers \SimpleSAML\XML\TestUtils\SchemaValidationTestTrait
 * @covers \SimpleSAML\XML\TestUtils\SerializableElementTestTrait
 * @covers \SimpleSAML\XML\ExtendableAttributesTrait
 *
 * @package simplesamlphp\xml-common
 */
final class ExtendableAttributesTraitTest extends TestCase
{
    use SerializableElementTestTrait;
    use SchemaValidationTestTrait;

    /** @var \SimpleSAML\XML\Attribute */
    protected Attribute $local;

    /** @var \SimpleSAML\XML\Attribute */
    protected Attribute $other;

    /** @var \SimpleSAML\XML\Attribute */
    protected Attribute $target;


    /**
     */
    public function setup(): void
    {
        $this->schema = dirname(__FILE__, 2) . '/resources/schemas/simplesamlphp.xsd';

        $this->testedClass = ExtendableAttributesElement::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 2) . '/resources/xml/ssp_ExtendableAttributesElement.xml',
        );

        $this->local = new Attribute(null, '', 'some', 'localValue');

        $this->target = new Attribute('urn:x-simplesamlphp:namespace', '', 'some', 'targetValue');

        $this->other = new Attribute('urn:custom:dummy', 'dummy', 'some', 'dummyValue');
    }


    /**
     */
    public function testInvalidNamespaceThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return 'wrong';
            }
        };
    }


    /**
     */
    public function testIllegalNamespaceComboThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_OTHER, C::XS_ANY_NS_ANY];
            }
        };
    }


    public function testEmptyNamespaceArrayThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [];
            }
        };
    }


    /**
     */
    public function testOtherNamespacePassingOtherSucceeds(): void
    {
        new class ([$this->other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_OTHER;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testOtherNamespacePassingLocalThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->local]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_OTHER;
            }
        };
    }


    /**
     */
    public function testTargetNamespacePassingTargetSucceeds(): void
    {
        new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_TARGET;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceeds(): void
    {
        new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_TARGET];
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingTargetArraySucceedsWithLocal(): void
    {
        new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return [C::XS_ANY_NS_TARGET, C::XS_ANY_NS_LOCAL];
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testTargetNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_TARGET;
            }
        };
    }


    /**
     */
    public function testLocalNamespacePassingLocalSucceeds(): void
    {
        $o = new class ([$this->local]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testLocalNamespacePassingLocalArraySucceeds(): void
    {
        new class ([$this->local]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testLocalNamespacePassingTargetThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAtributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };
    }



    /**
     */
    public function testLocalNamespacePassingOtherThrowsAnException(): void
    {
        $this->expectException(AssertionFailedException::class);
        new class ([$this->other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_LOCAL;
            }
        };
    }


    /**
     */
    public function testAnyNamespacePassingTargetSucceeds(): void
    {
        $o = new class ([$this->target]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->addToAssertionCount(1);
    }


    /**
     */
    public function testAnyNamespacePassingOtherSucceeds(): void
    {
        $o = new class ([$this->other]) extends ExtendableAttributesElement {
            public function getAttributeNamespace(): array|string
            {
                return C::XS_ANY_NS_ANY;
            }
        };

        $this->addToAssertionCount(1);
    }
}
