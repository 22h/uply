####################################################################################################
# https://www.gnu.org/software/make/                                                               #
####################################################################################################

$(eval deployerMaschine=$(shell hostname))
$(eval currentBranch=$(shell git symbolic-ref --short -q HEAD))

notificationPayload = {\
    \"embeds\":\
        \[\
            {\"title\":\"Webgamers $(env)\",\
            \"description\":\"Deploy Webgamers $(env).\",\
            \"color\":16738816\
            \}\
        \]\
}

localPath = .

rsyncSwitches = --checksum -rlvz4 --delete

rsyncLog = build/rsync.log

ifeq ($(env), stage)
	include Makefile.stage
endif

ifeq ($(env), prod)
	include Makefile.prod
endif

default:
	@echo "usage:      make env=(prod|stage) <rule>"
	@echo "list rules: make help"

help:
	@printf "list of rules:\n"
	@printf "  \033[0;36mdeploy\033[0m        deploy to instance\n"
	@printf "  \033[0;36mdeploy-lookup\033[0m deploy to instance (dry-run mode)\n"
	@printf "  \033[0;36mclear\033[0m         clear cache and tmp directories\n"

_prepare:
	@mkdir -p build

deploy-lookup: _prepare
	@printf "\033[0;36mdeploy-lookup:\033[0m\n"
	@RSYNC_PASSWORD=$(rsyncPass) \
	rsync -n $(rsyncSwitches) $(rsyncExcludes) $(localPath) \
	rsync://$(rsyncUser)@$(host)/$(rsyncShare)$(rsyncPath) | tee $(rsyncLog)
	@printf "\033[0;32m->see $(rsyncLog) for details\033[0m\n"

deploy: _deploy

_deploy:
	@printf "\033[0;36mdeploy:\033[0m\n\033[0m\n"
	@RSYNC_PASSWORD=$(rsyncPass) \
	    rsync $(rsyncSwitches) $(rsyncExcludes) $(localPath) \
	    rsync://$(rsyncUser)@$(host)/$(rsyncShare)$(rsyncPath) | tee $(rsyncLog)
	@printf "\033[0;32m->see $(rsyncLog) for details\033[0m\n"

discord-changelog:
	curl -H "Content-Type: application/json" --insecure -X POST --data-urlencode -d '{"embeds":[{"title":"Webgamers","description":"Wurde auf ... deployed","color":16738816}]}' \
	    https://discordapp.com/api/webhooks/416531530211524608/7Y0qvzVl9LxNpB2FXQYV1N6Ps8dFvSQAV7mF4R0DqCFBUXD9ik2ltWA8ZVeABDyaKuDt
