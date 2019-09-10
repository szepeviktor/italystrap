<?php
namespace ItalyStrap;

use ItalyStrap\Builders\Builder;
use function \ItalyStrap\Factory\injector;

class BuildersTest extends \Codeception\TestCase\WPTestCase
{

    public function setUp()
    {
        // before
        parent::setUp();

        // your set up methods here
    }

    public function tearDown()
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

	private function get_instance() {

		injector()->define( Builders\Builder::class,
			[
				':config'	=> '\ItalyStrap\Config\Config',
				':view'		=> '\ItalyStrap\Template\View',
			]
		);

		return injector()->make( Builders\Builder::class );
    }

    // tests
//    public function testItIsInstantiable()
//    {
//    	$class = Builders\Builder::class;
//    	$this->assertTrue( $this->get_instance() instanceof $class );
//    }

}