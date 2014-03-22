<?php

$I = new WebGuy($scenario);
$I->amOnPage('/');
$I->wantTo('perform a search');
$I->submitForm('#big-search', array('query' => 'UAV'));
$I->waitForElementVisible('//*[@id="results"]', 30); // secs

// Results are displayed.
$I->see('Department of the Air Force');
$I->see('Uav');
$I->see('Modification/Amendment');
$I->see('DESCRIPTIONThis is NOT a pre-solicitation notice pursuant to FAR Part 5, but a Sources Sought market survey to identify sources that are capable of providing specialized equipment and engineering services to the 96 TW for Small Unmanned Air vehicle Systems (SUAS) services to support Test Missions. The primary Missions will be conducted over both the Land and Water Ranges of the Eglin Complex. The Missions will be conducted in Restricted Air Space R2915A, R2915B, R2914A, and R2919A and Warning Ar');

//Facets are displayed.
$I->seeElement('#facets-list');

//Project details.
$I->seeLink('Details');
$I->click('Details');
$I->waitForElementVisible('//*[@id="myModal"]/div/div/div[2]', 30); // secs
$I->see('Last Updated');
$I->see('2013-Dec-05');
$I->see('Source');
$I->seeLink('FedBizOps.gov');
$I->see('Goods or Services');
$I->see('Goods');
$I->click('Close');
$I->waitForElementNotVisible('#myModal', 30); // secs

//POC
$I->seeLink('Monica Payton');
$I->click('Monica Payton');
$I->waitForElementVisible('//*[@id="myModal"]/div/div/div[2]', 30); // secs
$I->see('Email');
$I->seeLink('monica.payton@us.mil');
$I->see('Agency');
$I->see('Department of the Air Force');
$I->see('Office');
$I->see('Air Force Materiel Command');
$I->click('Close');
$I->waitForElementNotVisible('#myModal', 30); // secs