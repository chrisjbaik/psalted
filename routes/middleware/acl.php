<?php

/*
 * Roles:
 * 'guest', 'member', 'admin'
 */

$acl_middleware = function ( $role = 'member' ) {
    return function () use ( $role ) {
        if (empty($_SESSION['user']) || !$_SESSION['user']->hasRole($role)) {
            $app = \Slim\Slim::getInstance();
            throw new Exception('User is not authenticated for that.');
        }
    };
};