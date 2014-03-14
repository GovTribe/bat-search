<?php

$I = new WebGuy($scenario);
$I->amOnPage('/');
$I->wantTo('perform a search');
$I->submitForm('#big-search', array('query' => 'UAV'));
$I->waitForJS("return $.active == 0;", 60);
$I->see('Department of the Air Force');
$I->see('Uav');
$I->see('Modification/Amendment');
$I->seeLink('Details');
$I->see('DESCRIPTIONThis is NOT a pre-solicitation notice pursuant to FAR Part 5, but a Sources Sought market survey to identify sources that are capable of providing specialized equipment and engineering services to the 96 TW for Small Unmanned Air vehicle Systems (SUAS) services to support Test Missions. The primary Missions will be conducted over both the Land and Water Ranges of the Eglin Complex. The Missions will be conducted in Restricted Air Space R2915A, R2915B, R2914A, and R2919A and Warning Ar');
