<?phpuse Formbuilder\Controller\Action;use Formbuilder\Model\Form;use Formbuilder\Lib\Frontend as FormFrontEnd;use Formbuilder\Lib\Mailer;class Formbuilder_AjaxController extends Action{    public $languages = null;    public function parseAction()    {        $formId = $this->_getParam('_formId');        $locale = $this->_getParam('_language');        $templateId = $this->_getParam('_mailTemplate');        $valid = false;        $redirect = false;        $message = '';        $validationData = false;        $mainList = new Form();        $formData = $mainList->getById($formId);        if ($formData instanceof Form) {            $frontendLib = new FormFrontEnd();            $form = $frontendLib->getForm($formData->getId(), $locale, true);            $frontendLib->addDefaultValuesToForm(                $form,                ['formId' => $formId, 'locale' => $locale, 'mailTemplate' => $templateId]            );            $params = $frontendLib->parseFormParams($this->getAllParams(), $form);            $formValid = true;            $valid = false;            if ($frontendLib->hasRecaptchaV2()) {                $formValid = $form->isValid($params, $frontendLib->getRecaptchaV2Key());            }            if ($formValid === true) {                $valid = $form->isValid($params);            }            if ($valid) {                $assets = [];                $folder = "/Bewerbungen";                $parentId = \Pimcore\Model\Asset::getByPath($folder)->getId();                foreach ($_FILES as $content) {                    if (is_array($content['name'])) {                        // TODO: multiple files sent                    } else {                        $filename = strtolower($content['name']);                        $data = file_get_contents($content['tmp_name']);                        $asset = \Pimcore\Model\Asset::create(                            $parentId,  //Parent ID                            [                                "filename" => $filename,                                "data" => $data,                                "userOwner" => 1,                                "userModification" => 1,                            ]                        );                        $asset->save();                        $assets[] = $asset;                    }                }                if ($templateId !== null) {                    $send = Mailer::sendForm($templateId, ['data' => $form->getValues()], $assets);                    if ($send === true) {                        $return = $this->afterSend($templateId);                        $valid = $return['valid'];                        $redirect = $return['redirect'];                        $message = $valid === false ? $return['message'] : $return['html'];                    }                }                foreach ($assets as $asset) {                    $asset->delete();                }            } else {                $validationData = $form->getMessages();            }        }        $this->_helper->json(            [                'success' => $valid,                'message' => $message,                'validationData' => $validationData,                'redirect' => $redirect,            ]        );    }    private function afterSend($mailTemplateId)    {        $redirect = false;        $error = false;        $successMessage = '';        $statusMessage = '';        $mailTemplate = \Pimcore\Model\Document::getById($mailTemplateId);        $afterSuccess = $mailTemplate->getProperty('mail_successfully_sent');        //get the content from a snippet        if ($afterSuccess instanceof \Pimcore\Model\Document\Snippet) {            $params['document'] = $afterSuccess;            if ($this->view instanceof \Pimcore\View) {                try {                    $successMessage = $this->view->action(                        $afterSuccess->getAction(),                        $afterSuccess->getController(),                        $afterSuccess->getModule(),                        $params                    );                } catch (\Exception $e) {                    $error = true;                    $statusMessage = $e->getMessage();                }            }        } //it's a redirect!        else {            if ($afterSuccess instanceof \Pimcore\Model\Document) {                $redirect = true;                $successMessage = $afterSuccess->getFullPath();            } //it's just a string!            else {                if (is_string($afterSuccess)) {                    $successMessage = $afterSuccess;                }            }        }        return [            'valid' => $error === false,            'message' => $statusMessage,            'redirect' => $redirect,            'html' => $successMessage,        ];    }}