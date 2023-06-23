export default class VueFinalModal {
  vfm = null;
  closedInfo = {
    id : null,
    closeType : null,
  };

  constructor (vfm) {
    this.vfm = vfm;
  }

  getClosedInfo() {
    return this.closedInfo;
  }

  setClosedInfo(id, closeType) {
    this.closedInfo = {
      id,
      closeType,
    };
  }

  open (id, params = null) {
      this.vfm.open(id);
      if (params !== null) {
        this.setParams(id, params);
      }
  }

  close (id, closeType) {
    this.vfm.close(id);
    this.unsetParams(id);
    this.setClosedInfo(id, closeType);
    this.execClosedCallback(id);
    this.unsetClosedCallback(id);
  }

  setClosedCallback(id, callback) {
    const modal = this.get(id);
    modal.closedCallback = callback;
  }

  unsetClosedCallback(id) {
    const modal = this.get(id);
    delete modal.closedCallback
  }

  execClosedCallback(id) {
    const modal = this.get(id);
    if ('closedCallback' in modal) {
      modal.closedCallback();
    }
  }

  get (id) {
    return this.vfm.get(id);
  }

  setParams (id, params) {
    const modal = this.get(id);
    modal.params = params;
  }

  getParams(id) {
    const modal = this.get(id);
    return modal.params;
  }

  unsetParams (id) {
    const modal = this.get(id);
    if ('params' in modal) {
      delete modal.params;
    }
  }
}

