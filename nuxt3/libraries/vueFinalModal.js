export default class VueFinalModal {
  vfm = null;
  lastClosedModalId = "";

  constructor (vfm) {
    this.vfm = vfm;
  }

  open (id) {
      this.vfm.open(id);
  }

  close (id) {
      this.vfm.close(id);
  }

  get (id) {
    return this.vfm.get(id);
  }
}

