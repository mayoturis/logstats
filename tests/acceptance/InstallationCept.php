<?php

/*
use Page\Template;

$I = new AcceptanceTester($scenario);
$I->wantTo('Install logstats');

// Introduction
$I->amOnPage('/');
$I->see('Welcome', 'h1');
$I->seeInCurrentUrl('installation/1');
$I->click('Next step');

// Database setup
$I->seeInCurrentUrl('installation/2');
$I->selectOption('database_type', 'mysql');
$I->fillField('host', 'localhost');
$I->fillField('database', 'logstats_codeception_install');
$I->fillField('username', 'root');
$I->fillField('host', 'localhost');
$I->click('Save');

// Create tables
$I->seeInCurrentUrl('installation/3');
$I->see('Tables were successfully created');
$I->click('Next step');

// General setup
$I->seeInCurrentUrl('installation/4');
$I->fillField('name', 'some_user_name');
$I->fillField('password','password');
$I->fillField('password_confirmation','password');
$I->selectOption('timezone', 'Europe/Bratislava');
$I->fillField('project_name','some_project_name');
$I->click('Save');

// Congratulations
$I->seeInCurrentUrl('installation/5');
$I->see('Congratulations', 'h1');
$I->click('Home page');

// Home page
$I->seeIAmOnHomePage();
$I->see('some_user_name', Template::$loggedUserHodler);
$I->see('some_project_name', Template::$currentProjectHolder);*/