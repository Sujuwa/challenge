<?php

namespace SJW\SearchBundle\Tests\Services;

/**
 * Description of CityServiceTest
 *
 * @author Bogdan
 */

use SJW\SearchBundle\Services\SearchCityService;

class CityServiceTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testFileDoesNotExists(){
        
        $filepath = 'numbers.txt';
        
        $service = new SearchCityService($filepath);
        
        $service->getTextContentAsArray();
    }
    
}
