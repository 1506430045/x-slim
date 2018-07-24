<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'candy-api');

// Project repository
set('repository', 'https://github.com/thinkbitpro/candy-api.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);


// Hosts

host('10.10.30.21')
    ->set('deploy_path', '~/www/{{application}}');
    

// Tasks

task('reload:php-fpm', function () {
    run('sudo service php7.1-fpm restart');
});

desc('Deploy Candy-Api');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy', 'reload:php-fpm');
