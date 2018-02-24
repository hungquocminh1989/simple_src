<?php

//Register Controller Request
$controller = Flight::CommonController();
Flight::route('/(index)', array($controller, 'index'));
Flight::route('/hello', array($controller, 'hello'));
Flight::route('/obfuscator-javascript', array($controller, 'action_obfuscator'));

// Membership Controller
/*$membership = new MembershipController();
Flight::route('GET /login', array($membership, 'login'));
Flight::route('POST /login', array($membership, 'loginAttempt'));
Flight::route('/logout', array($membership, 'logout'));
Flight::route('/profile/@name', array($membership, 'profile'));
Flight::route('GET /profile/@name/edit', array($membership, 'profileEdit'));
Flight::route('POST /profile/@name/edit', array($membership, 'profileEditAttempt'));
Flight::route('GET /sign-up', array($membership, 'register'));
Flight::route('POST /sign-up', array($membership, 'registerAttempt'));*/

// catch everything
Flight::route('/*', array($controller, 'redirect'));