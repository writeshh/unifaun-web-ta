<?php

namespace Infab\UnifaunWebTa\Test;

use Mockery;
use Illuminate\Support\Collection;
use Infab\UnifaunWebTa\Test\TestCase;
use Infab\UnifaunWebTa\UnifaunClient;
use Infab\UnifaunWebTa\Unifaun;

class UnifaunTest extends TestCase
{
    protected $unifaunClient;
    protected $unifaun;

    public function setUp()
    {
        $this->unifaunClient = Mockery::mock(UnifaunClient::class);
        $this->unifaun = new Unifaun($this->unifaunClient);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test **/
    public function it_can_fetch_consignment_templates()
    {
        $this->unifaunClient
            ->shouldReceive('performQuery')
            ->once()
            ->andReturn([
                'result' => [
                    'consignmentTemplate' => [
                        'name' => 'vChain',
                        'description' => ''
                    ]
                ]
            ]);
        
        $response = $this->unifaun->getConsignmentTemplates();
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals('vChain', $response->first()['consignmentTemplate']['name']);
    }

    /** @test **/
    public function it_can_find_a_consignment_by_no()
    {
        $expectedArguments = [
            'ConsignmentResult',
            'findByConsignmentNo',
            [['key' => 'consignmentNo', 'value' => '123']]
        ];

        $this->unifaunClient
            ->shouldReceive('performQuery')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'consignments' => [
                        'Part' => [
                            0 => [
                                'Address' => [
                                    'id' => '181818',
                                    'name' => 'Infab'
                                ],
                                'Communication' => [
                                    'contactPerson' => 'Albin N',
                                    'phone' => '0733228083'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        
        $response = $this->unifaun->findByConsignmentNo('123');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals('Infab', $response->first()['consignments']['Part'][0]['Address']['name']);
    }

    /** @test **/
    public function it_can_get_a_consignment_by_id()
    {
        // Arrange
        
        $expectedArguments = [
            'ConsignmentResult',
            'findByConsignmentId',
            [['key' => 'consignmentId', 'value' => '1528808552694f191f478']]
        ];

        $this->unifaunClient
            ->shouldReceive('performQuery')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'consignments' => [
                        'Part' => [
                            0 => [
                                'Address' => [
                                    'id' => '181818',
                                    'name' => 'Infab'
                                ],
                                'Communication' => [
                                    'contactPerson' => 'Albin N',
                                    'phone' => '0733228083'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        
        $response = $this->unifaun->findByConsignmentId('1528808552694f191f478');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals('Infab', $response->first()['consignments']['Part'][0]['Address']['name']);
    }

    /** @test **/
    public function it_can_find_a_package_via_package_id()
    {
        $expectedArguments = [
            'ConsignmentResult',
            'findByPackageId',
            [['key' => 'packageId', 'value' => '373323997883182561']]
        ];
        $this->unifaunClient
            ->shouldReceive('performQuery')->withArgs($expectedArguments)
            ->once()
            ->andReturn([
                'result' => [
                    'consignments' => [
                        'Part' => [
                            0 => [
                                'Address' => [
                                    'id' => '181818',
                                    'name' => 'Infab'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    
        $response = $this->unifaun->findByPackageId('373323997883182561');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $response);
    }
}
