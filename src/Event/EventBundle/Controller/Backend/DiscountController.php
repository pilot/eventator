<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Entity\Discount;
use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Form\Type\DiscountType;

class DiscountController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Discount:index.html.twig', array(
            'discounts' => $this->getRepository('EventEventBundle:Discount')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $discount = new Discount();
            $discount->setType(Discount::TYPE_INFINITY);
            $discount->setAmount(0);
            $discount->setDiscount(1);
            $discount->setDateTo(new \DateTime());
        } else {
            $discount = $this->findOr404('EventEventBundle:Discount', $id);
        }

        $form = $this->createForm(DiscountType::class, $discount);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($discount);
                $this->getManager()->flush();

                $successFlashText = sprintf('Discount %s updated.', $discount->getName());
                if (!$id) {
                    $successFlashText = sprintf('Discount %s added.', $discount->getName());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_discount');
            }
        }

        return $this->render('EventEventBundle:Backend/Discount:manage.html.twig', [
            'discount' => $discount,
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();
        $translator = $this->get('translator');

        $entity = $this->findOr404('EventEventBundle:Discount', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash($translator->trans('Discount deleted.'));

        return $this->redirectToRoute('backend_discount');
    }


}
