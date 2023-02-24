## 環境構築

### 初回構築時

#### gitの設定

```
$ git config core.filemode false
$ git config --local core.hooksPath .githooks
```

#### コンテナの作成

```
$ docker-compose up -d
```

#### コンテナ内での作業

```
コンテナに入る
$ make sh

Laravel, Nuxtのビルド
$ make build

テストユーザーの生成
$ php artisan user:create

Nuxtのサーバ起動
$ make nw
```

以下のURLでアクセス可能になる。

https://127.0.0.1:8887/cloud-note/login

以下でログイン可能。

* mail : hoge@example.com
* pw : hoge

### 2回目以降

2回目以降は、
```
$ make sh
```
でコンテナに入って、
```
$ make nw
```
でNuxtのサーバを起動すればよい。
