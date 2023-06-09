include .env

all : help

help : 
	# make sh            Docker ContainerにShellで接続
	# make shdb          Docker ContainerにShellで接続後
	# build              ローカル環境のビルド
	# buildxserver       本番環境アップデート時のビルド
	# make inittetestdb  test用のデータベースをセットアップ
	# make nuxtwatch     開発時のVue自動ビルドを実行
	# make nw            nuxtwatchのエイリアス
	# make test          Laravelのテストを実行
	# make cert          Nuxt3の開発環境用の証明書を発行

sh :
	docker-compose exec php /bin/bash

shdb :
	docker-compose exec mysql /bin/bash -c " \
		mysql -u root -proot; \
	"

build :
	composer install
	cp .env.example .env
	chmod 777 -R ./storage/
	php artisan key:generate
	php artisan migrate
	php artisan db:seed
	cd nuxt && npm install && cp .env.example .env

buildxserver :
	git pull origin master
	composer install
	cd nuxt && npm install
	php artisan migrate
	cd nuxt && npm run build
	rm -rf ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note.back
	mv ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note.back
	cp -rf ${XSERVER_DOCUMENT_ROOT}/public_html/cloud_note/nuxt/dist ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note

initdb :
	docker-compose exec mysql /bin/bash -c " \
		mysql -u root -proot -e 'DROP DATABASE \`cloud_note\`;';\
		mysql -u root -proot -e 'CREATE DATABASE \`cloud_note\`;';\
		mysql -u root -proot -e 'GRANT ALL PRIVILEGES ON \`cloud_note\` .* TO \`docker\`@\`%\`;';\
	"
	docker-compose exec php /bin/bash -c ' \
		php artisan migrate; \
	'

inittestdb :
	docker-compose exec mysql /bin/bash -c " \
		mysql -u root -proot -e 'DROP DATABASE \`test_cloud_note\`;';\
		mysql -u root -proot -e 'CREATE DATABASE \`test_cloud_note\`;';\
		mysql -u root -proot -e 'GRANT ALL PRIVILEGES ON \`test_cloud_note\` .* TO \`docker\`@\`%\`;';\
	"
	docker-compose exec php /bin/bash -c ' \
		cp .env.testing.example .env.testing; \
		php artisan config:clear; \
		php artisan --env=testing key:generate; \
		php artisan --env=testing migrate;\
	'

createUser :
	docker-compose exec php /bin/bash -c ' \
		php artisan user:create; \
	'

cu : createUser

nuxtwatch :
	cd nuxt && npm run dev

nw : nuxtwatch

test :
	docker-compose exec php /bin/bash -c ' \
		php artisan config:clear; \
		php artisan test; \
	'

cert :
	docker-compose exec php /bin/bash -c ' \
		cd nuxt3/ssl && mkcert localhost; \
	'
