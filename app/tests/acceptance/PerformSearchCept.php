<?php

$I = new WebGuy($scenario);
$I->amOnPage('/');
$I->wantTo('perform a search');
$I->fillField('query','UAV');
$I->click('Submit');
$I->waitForJS("return $.active == 0;", 60);
$I->see('Department of the Air Force');