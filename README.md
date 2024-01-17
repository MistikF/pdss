### Cоздание базы данных для тестов
php bin/console doctrine:database:create --env=test
### Миграция базы данных для тестов
php bin/console doctrine:migrations:migrate -n --env=test
### Добавление фикстур в базу данных для тестов
php bin/console doctrine:fixtures:load --env=test
### Заполнение продуктов данными 
php bin/console products:update_data --env=test
### Запуск тестов
./vendor/bin/phpunit src/Tests/ProductControllerTest.php