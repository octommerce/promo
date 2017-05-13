<?php

Event::listen('cart.beforeAddItem',    'Octommerce\Promo\Listeners\ResetCouponOnCart');
Event::listen('cart.beforeUpdateItem', 'Octommerce\Promo\Listeners\ResetCouponOnCart');
Event::listen('cart.beforeRemoveItem', 'Octommerce\Promo\Listeners\ResetCouponOnCart');
