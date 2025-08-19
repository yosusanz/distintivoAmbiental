import concurrent.futures
import cv2
import easyocr
import re
import sys
import torch

from collections import defaultdict
from config import CLEAN_PLATE_REGEX, VALID_PLATE_REGEX, MAX_WORKERS

_reader = easyocr.Reader(['es'], gpu=torch.cuda.is_available())

try:
    for module in reader.detector.module_list:
        if hasattr(module, 'flatten_parameters'):
            module.flatten_parameters()
    for module in reader.recognizer.module_list:
        if hasattr(module, 'flatten_parameters'):
            module.flatten_parameters()
except Exception:
    pass

def taskProcessPlate(i: int, plate: dict) -> dict:
    results = extractTextFromPlateVersions({
        "crop": plate["crop"],
        "corrected": plate["corrected"],
        "blackWhite": plate["blackWhite"],
        "inverted": plate["inverted"],
        "filter1": plate["filter1"],
        "filter2": plate["filter2"],
        "filter3": plate["filter3"]
    }, label=f"(Matrícula {i})")

    best_plate, confidence = chooseBestPlate(results)

    return {
        "plateIndex": i,
        "plateImage": plate["croppedFileName"],
        "plate": best_plate if best_plate else None,
        "confidence": confidence
    }

def extractTextFromPlateVersions(plate_versions: dict, label: str = "") -> dict:
    results = {}

    def ocr_read(version_name, img):
        try:
            img = resizeIfNeeded(img)
            result = _reader.readtext(img)
            plates = []
            for (_, text, prob) in result:
                text_clean = re.sub(CLEAN_PLATE_REGEX, '', text.upper())
                if match := re.search(VALID_PLATE_REGEX, text_clean):
                    plates.append((match.group(0), prob))
            return version_name, plates
        except Exception as e:
            print(f"Error OCR en {label} - {version_name}: {e}", file=sys.stderr)
            return version_name, []

    with concurrent.futures.ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
        futures = [executor.submit(ocr_read, vn, img) for vn, img in plate_versions.items()]
        for future in concurrent.futures.as_completed(futures):
            version_name, plates = future.result()
            results[version_name] = plates

    return results

def chooseBestPlate(results: dict, min_confidence: float = 0.7) -> tuple[str, float]:
    plate_scores = defaultdict(list)

    for versionResults in results.values():
        for plate, confidence in versionResults:
            plate_clean = plate.replace(" ", "")
            plate_scores[plate_clean].append(confidence)

    if not plate_scores:
        return "", 0.0

    best_plate = ""
    best_score = 0.0
    for plate, confs in plate_scores.items():
        count = len(confs)
        avg_conf = sum(confs) / count
        score = avg_conf * count
        if score > best_score:
            best_score = score
            best_plate = plate

    best_confidence = sum(plate_scores[best_plate]) / len(plate_scores[best_plate])

    if len(best_plate) < 7 and best_confidence < 0.9:
        candidates = [(p, sum(confs)/len(confs)) for p, confs in plate_scores.items() if len(p) == 7 and (sum(confs)/len(confs)) > 0.7]
        if candidates:
            candidate_plate, candidate_conf = max(candidates, key=lambda x: x[1])
            return candidate_plate, candidate_conf

    return best_plate, best_confidence

def resizeIfNeeded(image, maxWidth=200):
    height, width = image.shape[:2]
    if width > maxWidth:
        scaleRatio = maxWidth / width
        newWidth = int(width * scaleRatio)
        newHeight = int(height * scaleRatio)
        image = cv2.resize(image, (newWidth, newHeight), interpolation=cv2.INTER_AREA)
    return image
