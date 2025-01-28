<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'Project-AMF-bakery');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('Joe870')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/AMF-bakery');

// Hooks

after('deploy:failed', 'deploy:unlock');
