<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;
use App\Utils\Repository\BaseRepository;

/**
 * @extends BaseRepository<Order>
 */
class OrderRepository extends BaseRepository
{
    public function __construct(private Order $order) {}

    protected function getModel(): Order
    {
        return $this->order;
    }
}
