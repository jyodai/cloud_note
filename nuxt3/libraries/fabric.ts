import { fabric } from 'fabric';

interface IFabric {
  loadCanvas(state: string): void;
  setUpdatedCallback(callback: () => void): void;
  changeDrawingMode(mode: boolean): void;
  zoomIn(): void;
  zoomOut(): void;
  undo(): void;
  redo(): void;
  clear(): void;
  remove(): void;
}

interface IState {
  version: string;
  objects: object[];
}

export default class Fabric implements IFabric {
  private canvas: fabric.Canvas;

  private zoomLevel = 1;

  private undoStack:Array<IState> = [];
  private redoStack:Array<IState> = [];
  private currentState: null|IState = null;

  private updatedCallback: (state: string) => void  = () => { return; };

  constructor(id: string) {
    this.canvas = new fabric.Canvas(id);
    this.canvas.setHeight(5000);
    this.canvas.setWidth(5000);
    this.canvas.setZoom(this.zoomLevel);
    this.canvas.isDrawingMode = true;

    this.canvas.freeDrawingBrush       = new fabric.PencilBrush(this.canvas);
    this.canvas.freeDrawingBrush.width = 1;
    this.canvas.freeDrawingBrush.color = "#EEEEEE";

    this.canvas.on('path:created', () => {
      this.addHitory();
      this.updated();
    });

    this.registerShortcut();
  }

  public loadCanvas(state: string) {
    this.canvas.loadFromJSON(JSON.parse(state), () => { return; });
    this.canvas.renderAll();
  }

  public setUpdatedCallback(callback: (state: string) => void) {
    this.updatedCallback = callback;
  }

  private addHitory () {
    if (this.currentState) {
      this.undoStack.push(this.currentState);
    }
    this.currentState = this.canvas.toJSON();
  }

  private registerShortcut() {
    const element = this.canvas.getElement().parentElement;
    element?.addEventListener('wheel', (event) => {
      const isCtrlPressed = event.ctrlKey;
      const delta         = event.deltaY;

      if (isCtrlPressed) {
        if (delta < 0) {
          this.zoomIn();
        } else if (delta > 0) {
          this.zoomOut();
        }
        event.preventDefault();
      }
    });
  }

  public changeDrawingMode (mode: boolean) {
    this.canvas.isDrawingMode = mode;
  }

  public zoomIn () {
    this.zoomLevel *= 1.2;
    this.canvas.setZoom(this.zoomLevel);
    this.canvas.renderAll();
  }

  public zoomOut () {
    this.zoomLevel /= 1.2;
    this.canvas.setZoom(this.zoomLevel);
    this.canvas.renderAll();
  }

  public undo () {
    if (this.undoStack.length > 0) {
      const state = this.undoStack.pop();
      this.redoStack.push(this.canvas.toJSON());
      this.canvas.loadFromJSON(state, () => { return; });
      this.canvas.renderAll();
      this.updated();
    }
  }

  public redo () {
    if (this.redoStack.length > 0) {
      const state = this.redoStack.pop();
      this.undoStack.push(this.canvas.toJSON());
      this.canvas.loadFromJSON(state, () => { return; });
      this.canvas.renderAll();
      this.updated();
    }
  }

  public clear() {
    if (!confirm('初期化しますか？')) {
      return;
    }
    const state = this.canvas.toJSON();
    this.undoStack.push(state);
    this.canvas.clear();
    this.updated();
  }

  public remove() {
    const selectedObjects = this.canvas.getActiveObjects();
    selectedObjects.forEach((obj) => {
      this.canvas.remove(obj);
    });
    this.updated();
  }

  public updated() {
    const state = this.canvas.toJSON();
    const str   = JSON.stringify(state);
    this.updatedCallback(str);
  }
}
