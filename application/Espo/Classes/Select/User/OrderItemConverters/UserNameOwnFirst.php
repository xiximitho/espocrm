<?php
/************************************************************************
 * This file is part of EspoCRM.
 *
 * EspoCRM - Open Source CRM application.
 * Copyright (C) 2014-2022 Yurii Kuznietsov, Taras Machyshyn, Oleksii Avramenko
 * Website: https://www.espocrm.com
 *
 * EspoCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * EspoCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with EspoCRM. If not, see http://www.gnu.org/licenses/.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "EspoCRM" word.
 ************************************************************************/

namespace Espo\Classes\Select\User\OrderItemConverters;

use Espo\Core\Select\Order\ItemConverter;
use Espo\Core\Select\Order\Item;

use Espo\ORM\Query\Part\OrderList;
use Espo\ORM\Query\Part\Order;
use Espo\ORM\Query\Part\Expression as Expr;

use Espo\Entities\User;

class UserNameOwnFirst implements ItemConverter
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function convert(Item $item): OrderList
    {
        return OrderList::create([
            Order
                ::create(
                    Expr::notEqual(
                        Expr::column('id'),
                        $this->user->getId()
                    )
                )
                ->withDirection($item->getOrder()),
            Order
                ::create(Expr::column('userName'))
                ->withDirection($item->getOrder()),
        ]);
    }
}
