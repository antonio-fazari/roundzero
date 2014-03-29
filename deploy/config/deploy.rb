# config valid only for Capistrano 3.1
lock '3.1.0'

set :application, 'roundzero'
set :repo_url, 'git@github.com:antonio-fazari/roundzero.git'

# Default branch is :master
# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

# Default deploy_to directory is /var/www/my_app
set :deploy_to, '/var/www/roundzero'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, %w{api/config.php}

# Default value for linked_dirs is []
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5

namespace :deploy do

  desc 'Install application'
  task :install do
    on roles(:web) do
      execute "cd '#{release_path}'; npm install"
      execute "cd '#{release_path}'; bower install"
      execute "cd '#{release_path}'; grunt build"
      execute "cd '#{release_path}/api'; composer.phar install"
    end
  end

  after :updated, :install

end
