<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Form\Type\SettingsType;
use Event\EventBundle\Entity\Event;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend:index.html.twig', array());
    }

    public function settingAction(Request $request)
    {
        $event = $this->getRepository('EventEventBundle:Event')->getEvent();
        
        if (!$event) {
            $event = new Event();
        }

        $form = $this->createForm(new SettingsType(), $event);    
        if ($request->isMethod('POST') && $form->handleRequest($request)) {

            if ($form->isValid()) {

                $em = $this->getManager();
                $em->persist($event);
                $em->flush();

                $this->setSuccessFlash('Event settings updated.');

                return $this->redirect($this->generateUrl('backend_setting'));
            }
        }

        return $this->render('EventEventBundle:Backend:setting.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function localeTabsAction()
    {
        return $this->render('EventEventBundle:Backend:_tabs.html.twig', array(
            'locales' => $this->container->getParameter('event.locales')
        ));
    }
}
