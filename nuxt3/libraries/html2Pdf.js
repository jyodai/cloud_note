import html2pdf from "html2pdf.js";

export default class Html2Pdf {
  element = null;
  cssClass = null;
  option  = {
    margin   : 2,
    filename : '',
    image    : { type : 'jpeg', quality : 1 },
    jsPDF    : { format : 'a4', orientation : 'portrait' },
  };

  constructor (element, fileName) {
    this.element         = element.cloneNode(true);
    this.option.filename = fileName + '.pdf';
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
