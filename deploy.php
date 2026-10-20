<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'hitch_log');

// Project repository
set('repository', 'git@github.com:AydinHassan/hitchlog.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', []);

// Shared storage is already writable by www-data, and deploy cannot change ACLs on its files
set('writable_mode', 'skip');
set('allow_anonymous_stats', false);

// Hosts

host('imp')
    ->set('remote_user', 'deploy')
    ->set('deploy_path', '/var/www/hitch.wildandwithout.com');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

