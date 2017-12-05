<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\ShowBlocks;
use Event\EventBundle\Form\Type\ShowBlocksType;

class ShowBlocksController extends Controller
{
    public function manageAction(Request $request)
    {
        $showBlocks = $this->getRepository('EventEventBundle:ShowBlocks')->findOrCreate();
        $translator = $this->get('translator');
        $form = $this->createForm(ShowBlocksType::class, $showBlocks);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                
                $this->getManager()->persist($showBlocks);
                $this->getManager()->flush();
               
                $this->setSuccessFlash($translator->trans('Settings were updated'));

                return $this->redirectToRoute('backend_show_blocks');
            }
        }

        return $this->render('EventEventBundle:Backend/ShowBlocks:manage.html.twig', [
            'showBlocks' => $showBlocks,
            'form' => $form->createView(),
        ]);
    }

}
