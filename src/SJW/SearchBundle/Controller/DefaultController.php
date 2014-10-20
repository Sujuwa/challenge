<?php

namespace SJW\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**     
     *
     * @Template()
     */
    public function indexAction($numberOfCities = null)
    {        
        return array(
            'numberOfCities' => $numberOfCities
        );
    }

    /**
     * @Route("/api/search")
     *
     * @Template()
     */
    public function searchAction(Request $request) {
        // Get the search string from the UI.
        $searchString = $request->query->get('q');

        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');        
        
        $cityService = new \SJW\SearchBundle\Services\SearchCityService($filePath);
        $searchResult = $cityService->searchCity($searchString);
        
        return $this->render("SJWSearchBundle:Default:results.html.twig", array(
            "results" => $searchResult
        ));
    }
    
    /**
     * @Route("api/autocomplete")     
     */
    public function autocompleteAction(Request $request){
        
        // Get the search string from the UI.
        $searchString = $request->query->get('q');

        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');
        
        $cityService = new \SJW\SearchBundle\Services\SearchCityService($filePath);
        $searchResult = $cityService->searchCity($searchString);
        return new JsonResponse($searchResult);
    }
    
    
    
    /**
     * @Route("/api/results")
     *     
     */
    public function resultsAction(Request $request){
        
        $kernel = $this->get('kernel');
        
        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers.txt');
        
        $searchArray = array(
            $request->request->get('zip')
            ,$request->request->get('city')
            ,$request->request->get('population')
        );
        
        $cityService = new \SJW\SearchBundle\Services\SearchCityService($filePath);
        
        $numberOfCities = $request->request->get('numberOfCities');        
        if(intval($numberOfCities) > 0) $cityService->setResultLimit ($numberOfCities);
        
        $searchResults = array();
        
        $searchResults = $cityService->getCitiesWithNearestPopulation($searchArray);        
        
        return $this->render("SJWSearchBundle:Default:results.html.twig", array(
            "results" => $searchResults
        ));
        
    }
    
    public function settingsAction(Request $request){
        
        $form = $this->createFormBuilder(array())
                ->add('cityNumber', 'text', array(
                    'label' => 'City Number'                    
                    ,'attr' => array(
                        'placeholder' => 'Enter a number'
                        ,'class' => 'form-control'
                    )
                    ,'constraints' => array(
                        new \Symfony\Component\Validator\Constraints\NotNull()
                        ,new \Symfony\Component\Validator\Constraints\Range(array(
                            'min' => 1
                        ))
                    )
                ))
                ->add('save', 'submit', array(
                    'label' => 'Save'
                    ,'attr' => array(
                        'class' => 'btn btn-default'
                    )
                ))
                ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid()){ 
            $data = $form->getData();            
            return $this->redirect($this->generateUrl('sjw_index', array('numberOfCities' => $data['cityNumber'])));            
        }
        
        return $this->render("SJWSearchBundle:Default:settings.html.twig", array(
            "form" => $form->createView()
        ));
        
    }
}
