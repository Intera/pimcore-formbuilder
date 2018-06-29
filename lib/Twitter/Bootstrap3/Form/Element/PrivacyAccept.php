<?php
/**
 * Twitter Bootstrap v.3 Form for Zend Framework v.1
 *
 * @category Forms
 * @package Twitter_Bootstrap3_Form
 * @subpackage Element
 * @author Ilya Serdyuk <ilya.serdyuk@youini.org>
 */

use Pimcore\Model\Document\Page;

/**
 * Privacy form element
 *
 * @category Forms
 * @package Twitter_Bootstrap3_Form
 * @subpackage Element
 */
class Twitter_Bootstrap3_Form_Element_PrivacyAccept extends Zend_Form_Element_Checkbox
{
    public function getLabel()
    {
        $label = parent::getLabel();

        $label = preg_replace_callback('#\{(.*?)\}#', function ($documentId) {
            $document = Page::getById($documentId[1]);
            if ($document instanceof Page) {
                return "<a href='" . $document->getFullPath() . "' target='_blank'>" .
                    $document->getTitle() .
                    "</a>";
            }
            return $documentId[1];
        }, $label);

        return $label;
    }
}
