FROM php:7.3-cli-alpine

LABEL "com.github.actions.name"="Update Dataset"
LABEL "com.github.actions.description"="Fetch download numbers and create new HTML Build"
LABEL "com.github.actions.icon"="check-circle"
LABEL "com.github.actions.color"="green"

ADD entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
