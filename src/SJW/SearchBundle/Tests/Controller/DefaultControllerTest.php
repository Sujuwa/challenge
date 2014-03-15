<?php

namespace SJW\SearchBundle\SJWSearchBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package SJW\SearchBundle\SJWSearchBundle\Tests\Controller
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Functional test for getting the index
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Finnish towns")')->count() > 0);
    }

    /**
     * Functional test for autocomplete
     * Search for string 'Lapp'
     */
    public function testAutocompleteForLapp()
    {
        $client = static::createClient();

        $client->request('GET', '/api/autocomplete?term=Lapp');

        $responseArray = json_decode($client->getResponse()->getContent(), true);
        $expected = array(
            0 =>
                array(
                    'id' => '62600',
                    'value' => 'Lappajärvi Keskus',
                    'label' => 'Lappajärvi Keskus (62600)',
                ),
            1 =>
                array(
                    'id' => '53100',
                    'value' => 'Lappeenranta keskus',
                    'label' => 'Lappeenranta keskus (53100)',
                ),
            2 =>
                array(
                    'id' => '53550',
                    'value' => 'Lappeenranta keskus',
                    'label' => 'Lappeenranta keskus (53550)',
                ),
            3 =>
                array(
                    'id' => '54260',
                    'value' => 'Lappeenranta keskus',
                    'label' => 'Lappeenranta keskus (54260)',
                ),
            4 =>
                array(
                    'id' => '54300',
                    'value' => 'Lappeenranta keskus',
                    'label' => 'Lappeenranta keskus (54300)',
                ),
            5 =>
                array(
                    'id' => '02590',
                    'value' => 'Lappers',
                    'label' => 'Lappers (02590)',
                ),
            6 =>
                array(
                    'id' => '27230',
                    'value' => 'Lappi',
                    'label' => 'Lappi (27230)',
                ),
            7 =>
                array(
                    'id' => '16670',
                    'value' => 'Lappila',
                    'label' => 'Lappila (16670)',
                ),
            8 =>
                array(
                    'id' => '10820',
                    'value' => 'Lappohja',
                    'label' => 'Lappohja (10820)',
                ),
            9 =>
                array(
                    'id' => '68840',
                    'value' => 'Nederlappfors',
                    'label' => 'Nederlappfors (68840)',
                ),
        );

        $this->assertEquals($expected, $responseArray);
    }

    /**
     * Functional test for search
     * Search for string '53100'
     */
    public function testSearchFor53100()
    {
        $client = static::createClient();

        $client->request('POST', '/api/search', array(
            'q' => '53100'
        ));

        $responseArray = json_decode($client->getResponse()->getContent(), true);
        $expected = array(
            77 =>
                array(
                    0 => '04300',
                    1 => 'Hyrylä',
                    2 => '10037',
                ),
            78 =>
                array(
                    0 => '33900',
                    1 => 'Härmälä-Rantaperkiö',
                    2 => '10019',
                ),
            79 =>
                array(
                    0 => '02770',
                    1 => 'Espoon Keskus Läntinen',
                    2 => '9958',
                ),
            80 =>
                array(
                    0 => '33560',
                    1 => 'Takahuhti',
                    2 => '9953',
                ),
            81 =>
                array(
                    0 => '53100',
                    1 => 'Lappeenranta keskus',
                    2 => '9908',
                    3 => true,
                ),
            82 =>
                array(
                    0 => '36200',
                    1 => 'Kangasala Keskus',
                    2 => '9810',
                ),
            83 =>
                array(
                    0 => '02600',
                    1 => 'Etelä-Leppävaara',
                    2 => '9724',
                ),
            84 =>
                array(
                    0 => '60200',
                    1 => 'Törnävä',
                    2 => '9685',
                ),
            85 =>
                array(
                    0 => '20740',
                    1 => 'Ilpoinen-Harittu',
                    2 => '9598',
                ),
            86 =>
                array(
                    0 => '00790',
                    1 => 'Viikki',
                    2 => '9541',
                ),
        );

        $this->assertEquals($expected, $responseArray['numbers']);
    }

} // end class