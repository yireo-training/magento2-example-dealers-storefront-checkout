<?php

declare(strict_types=1);

namespace Yireo\ExampleDealersStorefrontCheckout\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Yireo\ExampleDealers\Api\DealerRepositoryInterface;

/**
 * Class SelectOptions
 * @package Yireo\ExampleDealersStorefrontCheckout\Controller\Ajax
 */
class SelectOptions extends Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var DealerRepositoryInterface
     */
    private $dealerRepository;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param DealerRepositoryInterface $dealerRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        DealerRepositoryInterface $dealerRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->dealerRepository = $dealerRepository;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($this->getSelectOptions());
        return $resultJson;
    }

    /**
     * @return array
     */
    private function getSelectOptions(): array
    {
        $selectOptions = [];
        $dealers = $this->dealerRepository->getAll();
        foreach ($dealers as $dealer) {
            $selectOptions[] = [
                'id' => $dealer->getId(),
                'name' => $dealer->getName(),
                'address' => $dealer->getAddress(),
            ];
        }

        return $selectOptions;
    }
}
