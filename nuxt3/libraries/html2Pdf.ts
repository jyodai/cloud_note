import html2pdf from "html2pdf.js";

interface IHtml2Pdf {
  setCssClass(cssClass: string): void;
  output(): void;
}

interface Html2PdfOptions {
  margin: number;
  filename: string;
  image: { type: string; quality: number };
  jsPDF: { format: string; orientation: string };
}

export default class Html2Pdf implements IHtml2Pdf {
  private element: HTMLElement | null = null;
  private cssClass: string | null = null;
  private options: Html2PdfOptions = {
    margin   : 2,
    filename : '',
    image    : { type : 'jpeg', quality : 1 },
    jsPDF    : { format : 'a4', orientation : 'portrait' },
  };

  constructor(element: HTMLElement, fileName: string) {
    this.element          = element.cloneNode(true) as HTMLElement;
    this.options.filename = fileName + '.pdf';
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
      .set(this.options)
      .save()
      .then(() => {
        if (this.cssClass !== null) {
          this.element?.classList.remove(this.cssClass);
        }
      });
  }
}

