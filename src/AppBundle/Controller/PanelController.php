<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\PlaceForm;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Inject;
use AppBundle\Service\MealManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\PlaceManager;

use AppBundle\Repository\MealRepository;
use Knp\Component\Pager\Paginator;

/**
 * @Route("/panel")
 */
class PanelController extends Controller
{
    /**
     * @var MealManager
     * @Inject("service.mealManager")
    */
    protected $mealManager = null;

    /**
     * @var Translator
     * @Inject("translator.default")
    */
    protected $translator = null;

    /**
     * @var EntityManager
     * @Inject("doctrine.orm.default_entity_manager")
    */
    protected $entityManager = null;

    /**
     * @var PlaceManager
     * @Inject("service.placeManager")
    */
    protected $placeManager = null;


    /**
     * @var Paginator
     * @Inject("knp_paginator")
     */
	protected $paginator = null;
    
    /**
     * Short description
     *
     *
     * @Method({"GET","POST"})
     * @Security("has_role('ROLE_USER') and user.hasPlace()")
     * @Route("/edit", name="panel_edit", defaults = {})
     * @Template("AppBundle:Panel:edit.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     *
    */
    public function editAction(Request $request)
    {
        $form = $this -> createForm(new PlaceForm($this -> entityManager), $this -> getUser() -> getPlace(), array('method' => 'POST'));
        $form->handleRequest($request);

        if($form -> isValid())
        {
            $data = $form -> getData();
            $this -> placeManager -> edit($data);

            $this -> entityManager -> flush();
            $request -> getSession() -> getFlashBag() -> add('success', $this -> translator -> trans('panel.edit.success', [], 'messages'));
                    
            return $this->redirect($this->generateUrl('panel_index', array()));
        }

        return array(
            'form' => $form -> createView(),
        );
	}

    
    /**
     * Short description
     *
     *
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER') and user.hasPlace()")
     * @Route("", name="panel_index", defaults = {})
     * @Template("AppBundle:Panel:index.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     *
    */
    public function indexAction(Request $request)
    {
        $results = $this -> _getMealRepository() -> getPanelList(
			new \DateTime(),
			$this -> getUser() -> getPlace(),
			$request -> query -> get('type') ? $request -> query -> get('type') : null
        );
        $results = $this -> paginator -> paginate($results, $request -> query -> get('page', 1), 200);
        return array(
            'now' => new \DateTime(),
            'yesterday' => new \DateTime('-1 day'),
            'results' => $results,
        );
	}


    /**
     * @return MealRepository
    */
    protected function _getMealRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Meal");
    }



}