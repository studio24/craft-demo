<?php

namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'Craft demo site for W3C');

// Project repository
set('repository', 'git@github.com:studio24/craft-demo.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);
set('http_user', 'www-data');

// Shared files/dirs between deploys
set('shared_files', ['.env','config/license.key']);
set('shared_dirs', [
    'web/assets'

]);

// Writable dirs by web server
set('writable_dirs', ['web','config']);
set('allow_anonymous_stats', false);

// Custom
set('keep_releases', 10);

// Composer install for custom S24.

task('deploy:s24:composer',function(){
    cd('{{release_path}}');
    run('composer install');
    run('chmod 777 -R storage');
});

// Hosts

host('development')
    ->stage('development')
    ->hostname('128.30.54.149')
    ->user('studio24')
    ->set('deploy_path','/var/www/vhosts/craft-demo/development');





// Tasks

desc('Deploy Craft CMS Demo');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:s24:composer',
    'deploy:writable',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');


