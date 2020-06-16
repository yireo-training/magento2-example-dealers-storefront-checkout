<?php

declare(strict_types=1);

namespace Yireo\ExampleDealersStorefrontCheckout\Plugin;

use Magento\Checkout\CustomerData\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;

class AppendToCartCustomerData
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * AppendToCartCustomerData constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param Session $checkoutSession
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        Session $checkoutSession
    ) {
        $this->cartRepository = $cartRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param Cart $cartCustomerData
     * @param array $sectionData
     * @return array
     * @throws NoSuchEntityException
     */
    public function afterGetSectionData(Cart $cartCustomerData, array $sectionData): array
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        if (!$quoteId) {
            return $sectionData;
        }

        $cart = $this->cartRepository->get($quoteId);
        $preferredDealerId = (int)$cart->getPreferredDealerId();
        $sectionData['preferred_dealer_id'] = $preferredDealerId;

        return $sectionData;
    }
}
