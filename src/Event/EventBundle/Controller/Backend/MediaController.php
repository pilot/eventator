<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Media;
use Event\EventBundle\Form\Type\MediaType;

class MediaController extends Controller
{

    public function getUploadPath()
    {
        return $this->container->getParameter('media.uploadPath');
    }
    
    public function getUploadUrl(){
        return $this->container->getParameter('media.uploadUrl');
    }

    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Media:index.html.twig', array(
            'media' => $this->getRepository('EventEventBundle:Media')->findAll(),
            'uploadPath' => $this->getUploadPath(),
            'uploadUrl' => $this->getUploadUrl()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        $oldFileName = '';
        if ($id === null) {
            $media = new Media();
        } else {
            $media = $this->findOr404('EventEventBundle:Media', $id);
            $oldFileName = $media->getFilename();
        }

        $form = $this->createForm(MediaType::class, $media);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {


                if ($oldFileName !== $media->getFilename()) {
                    $url = $request->request->get('file_url');
                    $path = $this->getUploadPath();
                    $media->setFilename(time() . '_' . $media->getFilename());
                    $file = $this->getFile($url);
                    file_put_contents($path . $media->getFilename(), $file);
                    chmod($path . $media->getFilename(), 0777);
                    if (is_file($path . $oldFileName)) {
                        unlink($path . $oldFileName);
                    }
                }

                $this->getManager()->persist($media);
                $this->getManager()->flush();

                $successFlashText = sprintf('Media %s updated.', $media->getTitle());
                if (!$id) {
                    $successFlashText = sprintf('Media %s added.', $media->getTitle());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_media');
            }
        }

        return $this->render('EventEventBundle:Backend/Media:manage.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    }

    protected function getFile($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file = curl_exec($ch);
        curl_close($ch);

        return $file;
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Media', $id);
        $file = $this->getUploadPath() . $entity->getFilename();
        $this->getManager()->remove($entity);
        $this->getManager()->flush();
        if (is_file($file)) {
            unlink($file);
        }

        $this->setSuccessFlash('Media deleted.');

        return $this->redirectToRoute('backend_media');
    }
}
