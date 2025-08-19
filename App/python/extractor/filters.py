import cv2
import numpy as np
import os
import time

from config import CROP_PADDING
from typing import Optional, Dict

SHARPEN_KERNEL = np.array([[0, -1, 0],
                           [-1, 5,-1],
                           [0, -1, 0]])

def orderPoints(pts: np.ndarray) -> np.ndarray:
    rect = np.zeros((4, 2), dtype="float32")
    s = pts.sum(axis=1)
    rect[0] = pts[np.argmin(s)]
    rect[2] = pts[np.argmax(s)]

    diff = np.diff(pts, axis=1)
    rect[1] = pts[np.argmin(diff)]
    rect[3] = pts[np.argmax(diff)]
    
    return rect

def fourPointTransform(img: np.ndarray, pts: np.ndarray) -> np.ndarray:
    rect = orderPoints(pts)
    (tl, tr, br, bl) = rect

    widthA = np.linalg.norm(br - bl)
    widthB = np.linalg.norm(tr - tl)
    maxWidth = max(int(widthA), int(widthB))

    heightA = np.linalg.norm(tr - br)
    heightB = np.linalg.norm(tl - bl)
    maxHeight = max(int(heightA), int(heightB))

    if maxWidth == 0 or maxHeight == 0:
        return img

    dst = np.array([
        [0, 0],
        [maxWidth - 1, 0],
        [maxWidth - 1, maxHeight - 1],
        [0, maxHeight - 1]
    ], dtype="float32")

    M = cv2.getPerspectiveTransform(rect, dst)
    warped = cv2.warpPerspective(img, M, (maxWidth, maxHeight))

    return warped

def filterBw(img: np.ndarray) -> np.ndarray:
    """
    Convierte imagen a escala de grises y luego a binaria usando Otsu.
    """
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    _, bw = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    
    return bw

def applyClahe(gray: np.ndarray) -> np.ndarray:
    """
    Aplica CLAHE para mejorar contraste.
    """
    clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8, 8))

    return clahe.apply(gray)

def applyFilter1(img: np.ndarray) -> np.ndarray:
    """
    Mejora de contraste.
    """
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    enhanced = applyClahe(gray)
    
    return cv2.cvtColor(enhanced, cv2.COLOR_GRAY2BGR)

def applyFilter2(img: np.ndarray) -> np.ndarray:
    """
    Reducción de ruido, mejora de contraste y enfoque
    """
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    denoised = cv2.bilateralFilter(gray, 9, 75, 75)
    enhanced = applyClahe(denoised)

    kernel = SHARPEN_KERNEL
    sharpened = cv2.filter2D(enhanced, -1, kernel)

    return cv2.cvtColor(sharpened, cv2.COLOR_GRAY2BGR)

def applyFilter3(img: np.ndarray) -> np.ndarray:
    """
    Blanco/negro tras un pipeline completo de mejoras
    """
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    denoised = cv2.bilateralFilter(gray, 9, 75, 75)
    enhanced = applyClahe(denoised)

    kernel = SHARPEN_KERNEL
    sharpened = cv2.filter2D(enhanced, -1, kernel)

    binary = cv2.adaptiveThreshold(sharpened, 255,
                                   cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
                                   cv2.THRESH_BINARY, 11, 2)

    return cv2.cvtColor(binary, cv2.COLOR_GRAY2BGR)


def taskGetPlatesImages(i: int, detection, originalImage: np.ndarray, imagePath, results) -> Optional[Dict[str, np.ndarray]]:
    mask = results.masks.data[i].cpu().numpy()
    maskFull = cv2.resize(mask, (originalImage.shape[1], originalImage.shape[0]), interpolation=cv2.INTER_NEAREST)

    maskUint8 = (maskFull * 255).astype(np.uint8)
    contours, _ = cv2.findContours(maskUint8, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    if not contours:
        return None

    cnt = max(contours, key=cv2.contourArea)
    rect = cv2.minAreaRect(cnt)
    boxPoints = cv2.boxPoints(rect).astype(np.float32)

    x, y, w, h = cv2.boundingRect(cnt)
    xStart = max(x - CROP_PADDING, 0)
    yStart = max(y - CROP_PADDING, 0)
    xEnd = min(x + w + CROP_PADDING, originalImage.shape[1])
    yEnd = min(y + h + CROP_PADDING, originalImage.shape[0])

    croppedImg = originalImage[yStart:yEnd, xStart:xEnd]
    if croppedImg.size == 0:

        return None

    polygonLocal = boxPoints - [xStart, yStart]
    polygonLocal[:, 0] = np.clip(polygonLocal[:, 0], 0, croppedImg.shape[1] - 1)
    polygonLocal[:, 1] = np.clip(polygonLocal[:, 1], 0, croppedImg.shape[0] - 1)

    correctedImg = fourPointTransform(croppedImg, polygonLocal)
    if correctedImg.size == 0:

        return None

    blackWhite = filterBw(correctedImg)
    inverted = cv2.bitwise_not(blackWhite)
    filtered1 = applyFilter1(correctedImg)
    filtered2 = applyFilter2(correctedImg)
    filtered3 = applyFilter3(correctedImg)

    croppedFileName = savePlateImage(i, imagePath, originalImage, croppedImg)

    return {
        "croppedFileName": croppedFileName,
        "crop": croppedImg,
        "corrected": correctedImg,
        "blackWhite": blackWhite,
        "inverted": inverted,
        "filter1": filtered1,
        "filter2": filtered2,
        "filter3": filtered3,
    }

def savePlateImage(index, imagePath, originalImage, croppedImg):
    timestamp = time.strftime("%Y%m%d%H%M%S")
    originalFilename = os.path.basename(imagePath)
    filenameWithoutExt = os.path.splitext(originalFilename)[0]

    saveDir = "/var/www/html/images/uploads"
    os.makedirs(saveDir, exist_ok=True)

    fileName = f"{filenameWithoutExt} - {index}.jpg"
    cv2.imwrite(os.path.join(saveDir, fileName), croppedImg)
  
    return fileName
