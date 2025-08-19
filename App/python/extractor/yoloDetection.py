import logging
import sys

from config import MODEL_PATH
from ultralytics import YOLO

logging.getLogger('ultralytics').setLevel(logging.ERROR)

try:
    _model = YOLO(MODEL_PATH, verbose=False)
except Exception as e:
    print(f"No se pudo cargar el modelo YOLO: {e}", file=sys.stderr)

    _model = None

def getYoloResults(image):
    if _model is None:
        return None
    try:
        results = _model(image)[0]

        return results
    except Exception as e:
        print(f"Error al ejecutar YOLO: {e}", file=sys.stderr)

        return None
