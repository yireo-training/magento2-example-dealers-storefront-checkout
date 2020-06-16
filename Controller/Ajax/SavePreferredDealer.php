<?php

declare(strict_types=1);

namespace Yireo\ExampleDealersStorefrontCheckout\Controller\Ajax;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Class SavePreferredDealer
 * @package Yireo\ExampleDealersStorefrontCheckout\Controller\Ajax
 */
class SavePreferredDealer extends Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $request
     * @param CartRepositoryInterface $cartRepository
     * @param Session $checkoutSession
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        RequestInterface $request,
        CartRepositoryInterface $cartRepository,
        Session $checkoutSession
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->cartRepository = $cartRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return ResultInterface
     * @throws InputException
     */
    public function execute()
    {
        $preferredDealerId = (int)$this->request->getParam('id');
        if (empty($preferredDealerId)) {
            throw new InputException(__('Empty ID set'));
        }

        $this->savePreferredDealerId($preferredDealerId);

        /** @var Json $resultJson */
        $data = ['success' => 1];
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($data);
        return $resultJson;
    }

    /**
     * @param int $preferredDealerId
     * @return bool
     * @throws NoSuchEntityException
     */
    private function savePreferredDealerId(int $preferredDealerId): bool
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        if (!$quoteId) {
            return false;
        }

        $cart = $this->cartRepository->get($quoteId);
        $cart->setPreferredDealerId($preferredDealerId);
        $this->cartRepository->save($cart);
        return true;
    }
}
