ifneq ($(wildcard .env), .env)
$(shell cp .env.example .env)
endif

include .env

all : help

help : 
	# up                コンテナの起動
	# stop              コンテナの停止
	# restart           コンテナの再起動
	# sh                Docker ContainerにShellで接続
	# shdb              Docker ContainerにShellで接続後
	# build             ローカル環境のビルド
	# buildxserver      本番環境アップデート時のビルド
	# inittetestdb      test用のデータベースをセットアップ
	# create_admin_user 管理者ユーザーを作成
	# cau               create_admin_userのエイリアス
	# nuxtwatch         Nuxt3の開発用サーバを起動
	# nw                nuxtwatchのエイリアス
	# test              Laravelのテストを実行
	# cert              Nuxt3の開発環境用の証明書を発行
	# phpcs             phpcsを実行
	# phpcbf            phpcbfを実行
	# lint              ESLintでコードチェック
	# lintfix           ESLintでコード整形

up :
	docker-compose up -d

stop :
	docker-compose stop

reset : stop up

sh :
	docker-compose exec php /bin/bash

sh_root :
	docker-compose exec -u 0 php /bin/bash

shdb :
	docker-compose exec mysql /bin/bash -c " \
		mysql -u root -proot; \
	"

build :
	docker-compose exec php /bin/bash -c ' \
		composer install --no-interaction; \
		chmod 777 -R ./storage/; \
		php artisan key:generate; \
		php artisan migrate; \
		cd nuxt3 && npm install -y && cp .env.example .env; \
	'
	make cert

buildxserver :
	git pull origin master
	composer install
	cd nuxt3 && npm install
	php artisan migrate
	cd nuxt3 && npm run generate
	rm -rf ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note.back
	mv ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note.back
	cp -rf ${XSERVER_DOCUMENT_ROOT}/public_html/cloud_note/nuxt3/dist ${XSERVER_DOCUMENT_ROOT}/public_html/cloud-note

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

create_admin_user : 
	docker-compose exec php /bin/bash -c ' \
		php artisan user:create-admin; \
	'

cau : create_admin_user

nuxtwatch :
	docker-compose exec php /bin/bash -c ' \
		cd nuxt3 && npm run dev; \
	'

nw : nuxtwatch

nuxtgenerate :
	docker-compose exec php /bin/bash -c ' \
		cd nuxt3 && npm run generate; \
		mv dist ../cloud-note; \
	'

test :
	docker-compose exec php /bin/bash -c ' \
		php artisan config:clear; \
		php artisan test; \
	'

cert :
	docker-compose exec php /bin/bash -c ' \
		cd nuxt3/ssl && mkcert localhost; \
		chown -R 1000:1000 ./; \
	'

phpcs :
	docker-compose exec php /bin/bash -c ' \
		vendor/bin/phpcs; \
	'

phpcbf :
	docker-compose exec php /bin/bash -c ' \
		vendor/bin/phpcbf; \
	'

lint :
	docker-compose exec php /bin/bash -c ' \
		cd nuxt3 && npm run lint; \
	'

lintfix :
	docker-compose exec php /bin/bash -c ' \
		cd nuxt3 && npm run lintfix; \
	'
