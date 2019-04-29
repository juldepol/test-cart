<?php

use yii\helpers\Url;

class OrderCest
{
    public function ensureThatOrderPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/order'));        
        $I->see('Test Shop');
        $I->see('Hooray!');
        
        $I->seeLink('Go back to shopping');
        $I->click('Go back to shopping');
        $I->wait(2); // wait for page to be opened
        
        $I->see('Products');
    }
}
