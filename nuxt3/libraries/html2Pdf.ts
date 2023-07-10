import html2pdf from "html2pdf.js";

export default class Html2Pdf {
  private element: HTMLElement | null = null;
  private cssClass: string | null = null;
  private option = {
    margin   : 2,
    filename : '',
    image    : { type : 'jpeg', quality : 1 },
    jsPDF    : { format : 'a4', orientation : 'portrait' },
  };

  constructor(element: HTMLElement, fileName: string) {
    this.element         = element.cloneNode(true) as HTMLElement;
    this.option.filename = fileName + '.pdf';
  }

  public setCssClass(cssClass: string): void {
    this.cssClass = cssClass;
  }

  public output(): void {
    if (this.cssClass !== null) {
      this.element?.classList.add(this.cssClass);
    }
    html2pdf()
      .from(this.element)
      .set(this.option)
      .save()
      .then(() => {
        if (this.cssClass !== null) {
          this.element?.classList.remove(this.cssClass);
        }
      });
  }
}

