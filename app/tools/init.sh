composer install

./tools/wait-for-it.sh db:3306 -- echo "DB is up and running."

bin/console doctrine:database:drop --force
bin/console doctrine:database:create
bin/console doctrine:schema:create
