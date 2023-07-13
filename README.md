## 概要

手をかけずに分かりやすメモを残したいという願望から作成しました。

素早くメモを残せるように、以下のような機能を備えています。

* マークダウン記法
* シンタックスハイライト
* テキストでダイアグラムを作成
* vimによるキーバインド

![image](https://github.com/jyodai/cloud_note/assets/50922604/f44d1995-5e42-4239-8881-6571834339e3)


## 開発環境構築

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
