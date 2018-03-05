<?php

namespace AppBundle\Entity\Order;

use AppBundle\Entity\BasicEnum;

class OrderStatus extends BasicEnum {
    const WAITING = 'waiting';
    const WORK = 'work';
    const COMPLETE = 'complete';
    const UNCOMPLETE = 'uncomplete';
}
