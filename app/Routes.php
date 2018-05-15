<?php
/**
* 
* Cùng 1 Url có thể cho chạy nhiều route
* Thời gian check các route ko match rất nhanh
* Chạy qua route tiếp theo -> return TRUE
* Dừng tại route hiện tại -> return FAlSE
* 
*/

//Register Controller Request
$controller = new CommonController();
Flight::route('/(index)', array($controller, 'index'));
Flight::route('/hello', array($controller, 'hello'));
Flight::route('/javascript-obfuscator', array($controller, 'action_obfuscator'));

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

// Route mặc định khi không match toàn bộ
Flight::route('/*', array($controller, 'index'));