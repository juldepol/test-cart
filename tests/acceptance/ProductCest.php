<?php

use yii\helpers\Url;

class ProductsCest
{
    public function ensureThatProductsWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/products'));
        $I->see('Products', 'h3');
    }
}
