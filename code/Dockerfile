FROM docker.gitlab.tab-e.de/docker/docker-images/ilias/ilias-transientmysql:5.3.10

COPY ./plugin /var/www/html/ilias/Customizing/global/plugins/Modules/TestQuestionPool/Questions/assSQLQuestion

ENTRYPOINT ["/data/resources/transientmysql/entrypoint.sh"]
