export default class LoadingScreen {
  private screen: HTMLDivElement | null = null;
  private count = 0;

  constructor() {
    this.setLoadScreen();
  }

  private setLoadScreen(): void {
    const screen                 = document.createElement('div');
    screen.id                    = 'load-screen';
    screen.style.backgroundColor = 'rgb(33, 33, 33)';
    screen.style.opacity         = '0.46';
    screen.style.width           = '100%';
    screen.style.height          = '100%';
    screen.style.position        = 'absolute';
    screen.style.top             = '0';
    screen.style.bottom          = '0';
    screen.style.right           = '0';
    screen.style.left            = '0';
    screen.style.zIndex          = '9999';
    screen.style.display         = 'none';

    const loader          = document.createElement('div');
    loader.style.position = 'absolute';
    loader.style.bottom   = '2%';
    loader.style.right    = '2%';
    loader.innerHTML      = '...読み込み中...';

    screen.insertBefore(loader, screen.firstChild);

    this.screen = screen;

    const body = document.body;
    if (body) {
      body.insertBefore(this.screen, body.firstChild);
    }
  }

  public show(): void {
    this.count += 1;
    if (this.screen) {
      this.screen.style.display = 'block';
    }
  }

  public hide(): void {
    this.count -= 1;
    if (this.count === 0 && this.screen) {
      this.screen.style.display = 'none';
    }
  }
}

