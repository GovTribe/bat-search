<?php

$I = new WebGuy($scenario);
$I->amOnPage('/');
$I->wantTo('perform a search');
$I->submitForm('#big-search', array('query' => 'UAV'));
$I->waitForJS("return $.active == 0;", 60);
$I->see('Department of the Air Force');