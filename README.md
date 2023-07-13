## 概要

手をかけずに分かりやすメモを残したいという願望から作成しました。

素早くメモを残せるように、以下のような機能を備えています。

* マークダウン記法
* シンタックスハイライト
* テキストでダイアグラムを作成
* vimのキーバインド

![image](https://github.com/jyodai/cloud_note/assets/50922604/556dc0ce-fb86-477c-9264-83d901138875)

## 開発環境構築

### 前提条件

以下がインストール済み。

* Docker
* Docker Compose

### 初回構築時

#### gitの設定

```
$ git config --local core.hooksPath .githooks
```

#### コンテナの作成

```
$ make up
```

#### アプリケーションのセットアップ

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
