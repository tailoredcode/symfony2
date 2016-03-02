<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Form\NewsletterForm;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation\Inject;
use Doctrine\ORM\EntityManager;

use AppBundle\Repository\NewsletterRepository;

/**
 * @Route("/newsletter")
 */
class NewsletterController extends Controller
{
        /**
     * @var EntityManager
     * @Inject("doctrine.orm.default_entity_manager")
    */
    protected $entityManager = null;


    
    /**
     * Short description
     *
     * @ApiDoc(
     * 	description = "Newsletter:add description",
     * 	section = "Newsletter",
     * 	statusCodes = {
     * 		200 = "Success"
     * 	}
     * )
	 *
     * @Method({"POST"})
     *
     * @Route("", name="newsletter_add", defaults = {"_format" = "json"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return JsonResponse
     *
    */
    public function addAction(Request $request)
    {
        $form = $this -> createForm(new NewsletterForm(), null, array('method' => 'POST'));
    //$form -> add('_format', 'text', array('mapped' => false, 'required' => false));
        $form->handleRequest($request);
        
        if($form -> isValid())
        {
            $data = $form -> getData();

            $prevNewsletter = $this -> _getNewsletterRepository() -> findOneBy(array(
                'email' => $data -> getEmail()
            ));

            if(!is_object($prevNewsletter))
            {
                $this -> entityManager -> persist($data);
                $this -> entityManager -> flush();
            }

            return new JsonResponse(array(
                'msg' => 'ok', // $this -> translator -> trans('newsletter.add.success', [], 'messages'),                'newsletter' => $data -> toArray()
            ), 200);
        }

        return new JsonResponse(array(
            'status' => 200,
            'errors' => $this -> _getFormErrors($form),
        ), 400);
	}


    protected function _getFormErrors(Form $form)
	{
		$errors = array();

		if ($form instanceof Form) {
		    foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            foreach ($form->all() as $key => $child) {
                /** @var $child Form */
                if ($err = $this->_getFormErrors($child)) {
                    $errors[$key] = $err;
                }
            }
        }

        return $errors;
    }

    /**
     * @return NewsletterRepository
    */
    protected function _getNewsletterRepository()
    {
        return $this -> entityManager -> getRepository("AppBundle:Newsletter");
    }



}