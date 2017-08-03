<?php
namespace frontend\controllers;

use shop\services\ContactService;
use yii\web\Controller;
use shop\forms\ContactForm;
use yii;

class ContactController extends Controller
{
    private $contactService;

    public function __construct($id, $module, ContactService $contactService, $config = [])
    {
        $this->contactService = $contactService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Displays contact page.
     *
     */
    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->contactService->sendEmail($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $form,
            ]);
        }
    }
}