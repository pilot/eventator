<?php

namespace Event\EventBundle\Controller\Backend;

use Guzzle\Http\Client;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
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
                $media->setUpdated(new \DateTime());

                if ($oldFileName !== $media->getFilename()) {
                    $url = $request->request->get('file_url');
                    $path = $this->getUploadPath();
                    $media->setFilename(time() . '_' . $media->getFilename());
                    $file = $this->getFile($url);

                    $fs = new Filesystem();

                    try {
                        $fs->dumpFile($path . $media->getFilename(), $file, 0777);
                    } catch (IOExceptionInterface $e) {
                        throw new Exception($e->getMessage());
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
            'fs_api' => $this->container->getParameter('filestack.api_key'),
        ]);
    }

    protected function getFile($url)
    {
        $client = new Client();
        $file = $client->createRequest('GET', $url)->send()->getBody(true);

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
