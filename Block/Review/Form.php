<?php
/**
 * Copyright The Plum Tree Group.
 * Date: 10/2/18
 * Time: 6:15 PM
 */

namespace Ec\CustomPallet\Block\Review;

use Magento\Customer\Model\Context;

class Form extends \Magento\Review\Block\Form
{
    /**
     * Initialize review form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setAllowWriteReviewFlag(
            $this->httpContext->getValue(Context::CONTEXT_AUTH)
            || $this->_reviewData->getIsGuestAllowToWrite()
        );
        if (!$this->getAllowWriteReviewFlag()) {
            $queryParam = $this->urlEncoder->encode(
                $this->getUrl('*/*/*', ['_current' => true]) . '#review-form'
            );
            $this->setLoginLink(
                $this->getUrl(
                    'customer/account/login/',
                    [Url::REFERER_QUERY_PARAM_NAME => $queryParam]
                )
            );
        }

        $this->setTemplate('Magento_Review::form.phtml');
    }


    /**
     * Get review product id
     *
     * @return int
     */
    protected function getProductId()
    {
        if(null !== $this->getRequest()->getParam('product_id')){
            return $this->getRequest()->getParam('product_id', false);
        }else{
            return $this->getRequest()->getParam('id', false);
        }
    }

}