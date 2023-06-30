export default class LoadingScreen {
  screen = null;
  count  = 0;

  constructor () {
    this.#setLoadScreen();
  }

  #setLoadScreen () {
    const screen                 = document.createElement('div');
    screen.id                    = 'load-screen';
    screen.style.backgroundColor = 'rgb(33, 33, 33)';
    screen.style.opacity         = 0.46;
    screen.style.width           = '100%';
    screen.style.height          = '100%';
    screen.style.position        = 'absolute';
    screen.style.top             = 0;
    screen.style.bottom          = 0;
    screen.style.right           = 0;
    screen.style.left            = 0;
    screen.style.zIndex          = 9999;
    screen.style.display         = 'none';

    const loader          = document.createElement('div');
    loader.style.position = 'absolute';
    loader.style.bottom   = '2%';
    loader.style.right    = '2%';
    loader.innerHTML      = '...読み込み中...';

    screen.insertBefore(loader, screen.firstChild);

    this.screen = screen;

    document.body.insertBefore(
      this.screen, document.body.firstChild
    );
  }

  show () {
    this.count               += 1;
    this.screen.style.display = 'block';
  }

  hide () {
    this.count -= 1;
    if (this.count === 0) {
      this.screen.style.display = 'none';
    }
  }

}
