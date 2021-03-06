<?php
namespace Users\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Users\UsersBundle\Entity\Users;
use Users\UsersBundle\Form\MembercpContactType;
use Users\UsersBundle\Form\MembercpPreferencesType;
use Users\UsersBundle\Form\MembercpType;

class MembercpController extends Controller
{
    public function membercpAction(Request $request)
    {
        $userinfo = $this->get('security.token_storage')->getToken()->getUser();
        if ($userinfo == "anon.") {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->getCurrentUserId();

        $editForm = $this->createEditForm($user, $type = 'membercp');
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            if ($editForm->isValid()) {
                $em->flush();
                $flashmessage = 'Successfull Edit !';
                return $this->render('UsersBundle:Membercp:membercp.html.twig', array(
                    'user' => $user,
                    'form' => $editForm->createView(),
                    'flashmessage' => $flashmessage
                ));
            } else {
                throw new \Exception('Something went wrong!');
            }
        }

        return $this->render('UsersBundle:Membercp:membercp.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView()
        ));
    }

    public function membercpContactAction(Request $request)
    {
        $userinfo = $this->get('security.token_storage')->getToken()->getUser();
        if ($userinfo == "anon.") {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->getCurrentUserId();

        $editContactForm = $this->createEditForm($user, $type = 'contact');
        $editContactForm->handleRequest($request);

        if ($editContactForm->isSubmitted()) {
            if ($editContactForm->isValid()) {

                $em->flush();
                $flashmessage = 'Successfull Edit !';

                return $this->render('UsersBundle:Membercp:membercpcontact.html.twig', array(
                    'user' => $user,
                    'form' => $editContactForm->createView(),
                    'flashmessage' => $flashmessage
                ));
            } else {
                throw new \Exception('Something went wrong!');
            }
        }

        return $this->render('UsersBundle:Membercp:membercpcontact.html.twig', array(
            'user' => $user,
            'form' => $editContactForm->createView()
        ));
    }

    public function membercpPreferencesAction(Request $request)
    {
        $userinfo = $this->get('security.token_storage')->getToken()->getUser();
        if ($userinfo == "anon.") {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $user = $this->getCurrentUserId();

        $editPreferencesForm = $this->createEditForm($user, $type = 'preferences');
        $editPreferencesForm->handleRequest($request);

        if ($editPreferencesForm->isSubmitted()) {
            if ($editPreferencesForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $flashmessage = 'Successfull Edit !';

                return $this->render('UsersBundle:Membercp:membercppreferences.html.twig', array(
                    'user' => $user,
                    'form' => $editPreferencesForm->createView(),
                    'flashmessage' => $flashmessage
                ));
            } else {
                throw new \Exception('Something went wrong!');
            }
        }

        return $this->render('UsersBundle:Membercp:membercppreferences.html.twig', array(
            'user' => $user,
            'form' => $editPreferencesForm->createView()
        ));
    }

    public function membercpPrivacyAction() {
        return $this->render('UsersBundle:Membercp:membercpprivacy.html.twig');
    }

    public function membercpPasswordAction() {
        return $this->render('UsersBundle:Membercp:membercppassword.html.twig');
    }

    public function membercpSignatureAction() {
        return $this->render('UsersBundle:Membercp:membercpsignature.html.twig');
    }

    public function membercpAvatarAction() {
        return $this->render('UsersBundle:Membercp:membercpavatar.html.twig');
    }

    public function membercpInviteAction() {
        return $this->render('UsersBundle:Membercp:membercpinvite.html.twig');
    }

    public function membercpUpgradeAction() {
        return $this->render('UsersBundle:Membercp:membercpupgrade.html.twig');
    }

    public function membercpFollowAction() {
        return $this->render('UsersBundle:Membercp:membercpfollow.html.twig');
    }

    public function membercpGalleryAction() {
        return $this->render('UsersBundle:Membercp:membercpgallery.html.twig');
    }

    public function membercpPerformanceAction() {
        return $this->render('UsersBundle:Membercp:membercpperformance.html.twig');
    }

    public function membercpSubscribedAction() {
        return $this->render('UsersBundle:Membercp:membercpsubscribed.html.twig');
    }

    public function membercpPortcheckAction() {
        return $this->render('UsersBundle:Membercp:membercpportcheck.html.twig');
    }

    public function membercpAlertsAction() {
        return $this->render('UsersBundle:Membercp:membercpalerts.html.twig');
    }

    /**
     * Creates a form to edit a Produits entity.
     *
     * @param Users $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Users $user, $type)
    {
        if ($type == 'membercp') {
            $form = $this->createForm(MembercpType::class, $user);
        } elseif ($type == 'contact') {
            $form = $this->createForm(MembercpContactType::class, $user);
        } elseif ($type == 'preferences') {
            $form = $this->createForm(MembercpPreferencesType::class, $user);
        }

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Get cuurent user ID or redirect to login page
     *
     * @return null|object|\Symfony\Component\HttpFoundation\RedirectResponse|Users
     */
    private function getCurrentUserId()
    {
        $userinfo = $this->get('security.token_storage')->getToken()->getUser();
        if ($userinfo == "anon.") {
            return $this->redirectToRoute('fos_user_security_login');
        } else {
            $id = $userinfo->getId();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('UsersBundle:Users')->find($id);
            return $user;
        }
    }
}