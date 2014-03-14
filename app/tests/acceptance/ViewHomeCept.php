<?php

$I = new WebGuy($scenario);
$I->wantTo('view the home page');
$I->amOnPage('/');
$I->see('Data © ' . date("Y"));