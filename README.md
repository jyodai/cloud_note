## CloudNoteとは？

素早く記録を残すことに特化したオープンソースソフトウエアのメモアプリです。

階層構造でメモを保持し、マークダウン記法を使用してメモを残すことができます。

![スクリーンショット 2023-08-20 182725](https://github.com/jyodai/cloud_note/assets/50922604/f4653d05-65df-4036-9e31-5ce026a74da0)

## 機能

* メモの記録
  * マークダウン
  * シンタックスハイライト
  * ダイアグラム・チャート
  * 目次機能
  * pdf出力
  * vimによるキーバインド
* タスク管理
* 階層構造でのデータ管理
* 検索

## スクリーンショット

### シンタックスハイライト

![cloud_note_シンタックスハイライト](https://github.com/jyodai/cloud_note/assets/50922604/da8624d3-2b88-4567-8f3a-5340eafc5ba9)


### ダイアグラム

![cloud_note_ダイアグラム](https://github.com/jyodai/cloud_note/assets/50922604/a41a535c-6988-4779-a67b-026c1250cbda)

### タスク

![cloud_note_タスク機能](https://github.com/jyodai/cloud_note/assets/50922604/448574dd-3d9c-4be4-886f-c55c4dc297a9)

## クイックスタート

### 前提条件

以下がインストール済み。

* Docker
* Docker Compose

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

$ make nuxtgenerate
```

CloudNoteにアクセスする。

https://127.0.0.1/cloud-note/login
