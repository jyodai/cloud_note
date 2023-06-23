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
  }

  get (id) {
    return this.vfm.get(id);
  }

  setParams (id, params) {
    const modal = this.get(id);
    modal.params = params;
  }

  unsetParams (id) {
    const modal = this.get(id);
    if ('params' in modal) {
      modal.params = {};
    }
  }
}

