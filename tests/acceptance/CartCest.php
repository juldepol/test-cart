<?php

use yii\helpers\Url;

class CartCest
{
    public function ensureThatCartWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/cart'));
        $I->see('Cart', 'h3');
    }
}
