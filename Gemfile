source "https://rubygems.org"
ruby RUBY_VERSION

# Hello! This is where you manage which Jekyll version is used to run.
# When you want to use a different version, change it below, save the
# file and run `bundle install`. Run Jekyll with `bundle exec`, like so:
#
#     bundle exec jekyll serve
#
# This will help ensure the proper Jekyll version is running.
# Happy Jekylling!
gem "jekyll"

# This is the default theme for new Jekyll sites. You may change this to anything you like.
gem "minima"
#gem "midnight-jekyll-theme"

# If you want to use GitHub Pages, remove the "gem "jekyll"" above and
# uncomment the line below. To upgrade, run `bundle update github-pages`.
gem "github-pages", group: :jekyll_plugins

# If you have any plugins, put them here!
group :jekyll_plugins do
  gem "jekyll-github-metadata", "~> 1.0"
end

group :development do
    # personal watcher
    gem 'guard'
    # custom rake tasks
    gem 'guard-rake'
    # reload jekyll server
    # not true in windows, due to fork() system function unexists
    # gem 'guard-jekyll-plus'

    # windows polyfill
    gem 'wdm', '>= 0.1.1' if Gem.win_platform?

    # to make personal autoreload websocket based
    gem "enomis-websocket", github: "SimoSca/rubygem-enomis-websocket", branch: "master"
    # to call server, both http and websocket, as daemon
    gem 'daemons' # not working in windows
end
