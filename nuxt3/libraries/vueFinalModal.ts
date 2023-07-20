import { Vfm, Modal} from 'vue-final-modal';

interface ExtendedModal extends Modal {
  params?: object;
  closedCallback?: () => void;
}

interface ClosedInfo {
  id: string | null;
  closeType: number | null;
}

export default class VueFinalModal {
  private vfm: Vfm;
  private closedInfo: ClosedInfo = {
    id        : null,
    closeType : null,
  };

  constructor(vfm: Vfm) {
    this.vfm = vfm;
  }

  public get(id: string): ExtendedModal {
    const modal = this.vfm.get(id) as ExtendedModal | undefined;
    if (!modal) {
      throw new Error(`Modal ID '${id}' not found.`);
    }
    return modal;
  }

  public open(id: string, params: object | null = null): void {
    this.vfm.open(id);
    if (params !== null) {
      this.setParams(id, params);
    }
  }

  public close(id: string, closeType: number): void {
    this.vfm.close(id);
    this.unsetParams(id);
    this.setClosedInfo(id, closeType);
    this.execClosedCallback(id);
    this.unsetClosedCallback(id);
  }

  public setClosedCallback(id: string, callback: () => void): void {
    const modal          = this.get(id);
    modal.closedCallback = callback;
  }

  public getClosedInfo(): object {
    return this.closedInfo;
  }

  private setClosedInfo(id: string, closeType: number): void {
    this.closedInfo = {
      id,
      closeType,
    };
  }

  private unsetClosedCallback(id: string): void {
    const modal = this.get(id);
    delete modal.closedCallback;
  }

  private execClosedCallback(id: string): void {
    const modal = this.get(id);
    if (modal.closedCallback) {
      modal.closedCallback();
    }
  }

  public setParams(id: string, params: object): void {
    const modal  = this.get(id);
    modal.params = params;
  }

  public getParams(id: string): object {
    const modal = this.get(id);
    if (modal.params) {
      return modal.params;
    }
    return {};
  }

  private unsetParams(id: string): void {
    const modal = this.get(id);
    if ('params' in modal) {
      delete modal.params;
    }
  }
}

