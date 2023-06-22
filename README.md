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

#### 開発環境の構築

```
Laravel, Nuxtのビルド
$ make build

管理者ユーザーの生成(※パスワードを記録を忘れないようにする)
$ make create_admin_user

Nuxtのサーバ起動
$ make nw
```

以下のURLでアクセス可能になる。

https://127.0.0.1:8887/cloud-note/login
