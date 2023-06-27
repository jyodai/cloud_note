import html2pdf from "html2pdf.js";

export default class Html2Pdf {
  element = null;
  cssClass = null;
  option  = {
    margin   : 2,
    filename : 'testfileName.pdf',
    image    : { type : 'jpeg', quality : 1 },
    jsPDF    : { format : 'a4', orientation : 'portrait' },
  };

  constructor (element) {
    this.element = element.cloneNode(true);
  }

  setCssClass (cssClass) {
    this.cssClass = cssClass;
  }

  output () {
    if (this.class !== null) {
      this.element.classList.add(this.cssClass);
    }
    html2pdf()
      .from(this.element)
      .set(this.option)
      .save()
      .then(() => {
        if (this.class !== null) {
          this.element.classList.remove(this.cssClass);
        }
      });
    
  }
}
