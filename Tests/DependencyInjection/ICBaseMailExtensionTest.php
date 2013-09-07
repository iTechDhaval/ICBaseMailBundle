<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\MailBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use IC\Bundle\Base\MailBundle\DependencyInjection\ICBaseMailExtension;

/**
 * Test for ICBaseMailExtension
 *
 * @group DependencyInjection
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class ICBaseMailExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $containerBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->containerBuilder = new ContainerBuilder();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->containerBuilder);
    }

    /**
     * @dataProvider provideDataForInvalidConfiguration
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidConfiguration(array $config)
    {
        $loader = new ICBaseMailExtension();

        $loader->load(array($config), $this->containerBuilder);
    }

    public function provideDataForInvalidConfiguration()
    {
        return array(
            array(
                array(
                    'composer'    => array()
                )
            ),
        );
    }

    public function testValidConfiguration()
    {
        $this->createFullConfiguration();

        $this->assertParameter('John Smith', 'ic_base_mail.composer.default_sender.name');

        $this->assertDICConstructorArguments('ic_base_mail.service.composer', array());
        $this->assertDICConstructorArguments('ic_base_mail.service.sender', array());
    }

    private function createFullConfiguration()
    {
        $config = array(
            'composer'    => array(
                'default_sender' => array(
                    'name'    => 'John Smith',
                    'address' => 'jsmith@nationfibre.net'
                )
            )
        );

        $loader = new ICBaseMailExtension();

        $loader->load(array($config), $this->containerBuilder);
    }

    private function assertParameter($value, $key)
    {
        $this->assertEquals(
            $value,
            $this->containerBuilder->getParameter($key),
            sprintf('%s parameter is correct', $key)
        );
    }

    private function assertDICConstructorArguments($id, $args)
    {
        $definition = $this->containerBuilder->getDefinition($id);

        $this->assertEquals(
            $args,
            $definition->getArguments(),
            sprintf(
                'Expected and actual DIC Service constructor arguments of definition "%s" do not match.',
                $definition->getClass()
            )
        );
    }
}