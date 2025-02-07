<?php

namespace Dragonfly\OrderGridPhone\Ui\Component\Listing\Column;

use Dragonfly\WithoutPhoneCall\Service\Settings;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Telephone extends Column
{
    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface         $context,
        UiComponentFactory       $uiComponentFactory,
        OrderRepositoryInterface $orderRepository,
        array                    $components = [],
        array                    $data = []
    )
    {
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $order = $this->orderRepository->get($item["entity_id"]);
                $telephone = $order->getBillingAddress()->getTelephone();
                $withoutPhoneCallType = $order->getData('without_phone_call');
                $withoutPhoneCall = Settings::TYPE_HTML[$withoutPhoneCallType];
                $item[$this->getData('name')] = $withoutPhoneCall . $telephone;
            }
        }

        return $dataSource;
    }
}
