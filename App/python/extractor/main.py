import config
import cv2
import json
import os
import sys
import torch

from concurrent.futures import ThreadPoolExecutor, as_completed
from filters import taskGetPlatesImages
from ocr import taskProcessPlate
from yoloDetection import getYoloResults

def main():
    if len(sys.argv) < 2:
        print("Uso: python main.py <imagen>")
        sys.exit(1)

    results = processImage(sys.argv[1])
    if not results:
        print(json.dumps([]))
        sys.exit(1)

    print(json.dumps(results, indent=2))


def processImage(imagePath: str) -> list:
    originalImage = cv2.imread(imagePath)
    if originalImage is None:
        return []

    yoloResults = getYoloResults(originalImage)

    numberOfDetectedPlates = len(yoloResults.boxes)
    if numberOfDetectedPlates == 0:
        return []

    config.MAX_WORKERS = calculateMaxWorkers(numberOfDetectedPlates)

    processedPlates = []
    with ThreadPoolExecutor() as executor:
        futures = [
            executor.submit(taskGetPlatesImages, i, det, originalImage, imagePath, yoloResults)
            for i, det in enumerate(yoloResults.boxes)
        ]

        for future in as_completed(futures):
            try:
                plateImages = future.result()
                if plateImages is not None:
                    processedPlates.append(plateImages)
            except Exception as e:
                print(f"[ERROR] taskGetPlatesImages fallo: {e}", file=sys.stderr)

                return []

    platesJson = []
    with ThreadPoolExecutor() as executor:
        futures = [
            executor.submit(taskProcessPlate, i, plate)
            for i, plate in enumerate(processedPlates, 1)
        ]
        
        for future in as_completed(futures):
            try:
                ocrResult = future.result()
                platesJson.append(ocrResult)
            except Exception as e:
                print(f"[ERROR] taskProcessPlate fallo: {e}", file=sys.stderr)

                return []
            
    return platesJson

def calculateMaxWorkers(numPlates: int, maxImagesPerPlate: int = 7) -> int:
    numCpu = os.cpu_count() or 1
    numGpu = torch.cuda.device_count()

    totalTasks = numPlates * maxImagesPerPlate

    if numGpu > 0:
        maxWorkers = min(2, numCpu, totalTasks)
    else:
        maxWorkers = min(4, numCpu, totalTasks)

    return maxWorkers

if __name__ == "__main__":
    main()
