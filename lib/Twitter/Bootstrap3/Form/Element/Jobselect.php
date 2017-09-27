<?php


use Pimcore\Model\Object\Stellenangebot;
/**
 * JobSelect form element
 *
 */
class Twitter_Bootstrap3_Form_Element_Jobselect extends Zend_Form_Element_Select
{
    /**
     * Separator to use between options; defaults to ''.
     * @var string
     */
    protected $_separator = '';

    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);

        $jobs = new Stellenangebot\Listing();
        $jobs->load();

        $options = [];

        foreach ($jobs as $job){
            $options[$job->getId()] = $job->getTitle();
        }

        $this->options = $options;
    }

}
