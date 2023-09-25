import { fabric } from 'fabric';

interface IFabric {
  loadCanvas(state: string): void;
  setUpdatedCallback(callback: () => void): void;
  setZoomCallback(callback: () => void): void;
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

  private zoomLevel = 1.0;
  private zoomStep  = 0.1;
  private maxZoomLevel = 2.0;
  private minZoomLevel = 0.1;

  private undoStack:Array<IState> = [];
  private redoStack:Array<IState> = [];
  private currentState: null|IState = null;

  private updatedCallback: (state: string) => void  = () => { return; };
  private zoomCallback: (zoomLevel: number) => void  = () => { return; };

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

  public setZoomCallback(callback: (zoomLevel: number) => void) {
    this.zoomCallback = callback;
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
    if (this.zoomLevel === this.maxZoomLevel) {
      return;
    }
    this.zoomLevel += this.zoomStep;
    this.zoomExec();
  }

  public zoomOut () {
    if (this.zoomLevel === this.minZoomLevel) {
      return;
    }
    this.zoomLevel -= this.zoomStep;
    this.zoomExec();
  }

  private zoomExec () {
    this.zoomLevel = Math.round(this.zoomLevel * 10) / 10;
    this.canvas.setZoom(this.zoomLevel);
    this.canvas.renderAll();
    this.zoomCallback(this.zoomLevel);
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
