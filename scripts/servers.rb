# Works only in Linux like Machine,
# since Daemons use "fork()" function, not present in window.

# Script to execute as Daemon, via
# => bundle exec ruby servers.start
# and stop via
# => bundle exec ruby servers.start
#
# see
# => https://github.com/thuehlinger/daemons

require 'daemons'

task1 = Daemons.call(:multiple => true) do
  # first server task

    Bundler.with_clean_env do
        `brew install wget`
    end
end

task2 = Daemons.call do
  # second server task

    loop do
        puts "task2"
        sleep(5)
    end
end

# we can even control our tasks, for example stop them
# task1.stop
# task2.stop
