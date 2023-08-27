interface IShortcutKey {
  handleKeyDown(event: KeyboardEvent): void;
}

export default class ShortcutKey implements IShortcutKey {
  private eventKey: string|null = null;
  private callback: () => void  = () => { return; };

  constructor(eventKey: string, callback: () => void) {
    this.eventKey      = eventKey;
    this.callback      = callback;
    this.handleKeyDown = this.handleKeyDown.bind(this);
  }

  public handleKeyDown(event: KeyboardEvent): void
  {
    if (event.ctrlKey && event.key === this.eventKey) {
      event.preventDefault();
      event.stopPropagation();
      this.callback();
    }
  }
}
