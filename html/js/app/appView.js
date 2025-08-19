export class AppView{

	constructor(controller) {
		this.controller = controller;

		const imageContainer = document.getElementById("image-container");
		const imageInput = document.getElementById("image-input");

		imageContainer.addEventListener("click", () => {
			imageInput.click();
		});

		imageInput.addEventListener("change", () => {
			const file = imageInput.files[0];

			this.imageLoaded(file)
		});

		const obtainEnvironmentalCodesButton = document.getElementById("obtain-environmental-codes");
		obtainEnvironmentalCodesButton.addEventListener("click", () => {
			this.showLoader();
			this.resetSections();
			this.obtainEnvironmentalCodes();
		});
	}	

	async init() {
		this.resetApp();
		this.hideSections();
	}

		
	showLoader() {
		document.getElementById('loading-background').style.display = 'flex';
		document.getElementById('loading-container').style.display = 'flex';
	}
	hideLoader() {
		document.getElementById('loading-background').style.display = 'none';
		document.getElementById('loading-container').style.display = 'none';
	}

	resetApp() {
		document.getElementById('please-load-photo').style.display = 'block';
		document.getElementById('obtain-environmental-codes').style.display = 'none';

		this.resetSections();
	}
	resetSections() {
		const platesTable = document.querySelector(".plates-table");
		const rows = platesTable.querySelectorAll(".table-row:not(.header)");
		rows.forEach(row => row.remove());

		document.getElementById('plates-buttons-container').style.visibility = 'hidden';

		const stickersContainer = document.querySelector(".stickers");
		const stickers = stickersContainer.querySelectorAll(".sticker");
		stickers.forEach(sticker => sticker.remove());
	}
	hideSections() {
		document.getElementById('plates-section').style.visibility = 'hidden';
		document.getElementById('stickers-section').style.visibility = 'hidden';
	}

	imageLoaded(file) {
		const preview = document.getElementById("image-preview");

		if (file) {
			const reader = new FileReader();
			reader.onload = e => {
				preview.src = e.target.result;

				this.resetSections();
				this.hideSections();
				this.imageLoadedRefreshUI()
			};

			reader.readAsDataURL(file);
		}
	}

	imageLoadedRefreshUI() {
		const imageContainer = document.getElementById("image-container");
		const preview = document.getElementById("image-preview");

		imageContainer.classList.add("image-loaded");
		preview.classList.add("image-loaded");

		document.getElementById('please-load-photo').style.display = 'none';
		document.getElementById('obtain-environmental-codes').style.display = 'block';
	}

	async obtainEnvironmentalCodes() {
		const loadedImage = document.getElementById('image-input');  
		const file = loadedImage.files[0];
		if (!file) {
			const errorText = 'Selecciona una imagen primero.';
			this.controller.showToast({type: 'warning', text: errorText});

			return;
		}

		const response = await this.controller.callApi('platesService', 'getEnvironmentalCodesByImage', null, [file]);
		if (response) {
			if (response.total === 0) {
				const errorText = 'No se encontraron matrículas en la imagen';
				this.controller.showToast({type: 'warning', text: errorText});
			} else {
				this.drawPlates(response.data);
				this.drawCodes(response.data);
			}
		}

		this.hideLoader();
	}

	drawPlates(plates) {
		const table = document.querySelector('#plates-section .plates-table');
		
		plates.forEach(plate => {
			const row = document.createElement('div');
			row.className = 'table-row';
			row.setAttribute('data-plate-index', plate.plateIndex);

			const imgPlate = document.createElement('img');
			imgPlate.className = 'plate-image';
			imgPlate.src = `./images/uploads/${plate.plateImage}`;
			imgPlate.alt = plate.plate;

			const spanPlate = document.createElement('span');
			spanPlate.textContent = plate.plate;

			const spanConfidence = document.createElement('span');
			const confidenceValue = (plate.confidence * 100).toFixed(0);
			if (confidenceValue < 40) {
				spanConfidence.className = 'confidence-low';
			} else if (confidenceValue < 70) {
				spanConfidence.className = 'confidence-medium';
			} else {
				spanConfidence.className = 'confidence-high';
			}
			spanConfidence.textContent = `${confidenceValue}%`;

			row.appendChild(imgPlate);
			row.appendChild(spanPlate);
			row.appendChild(spanConfidence);

			table.appendChild(row);
		});

		document.getElementById('plates-section').style.visibility = 'visible';
	}
	
	drawCodes(plates) {
		const container = document.querySelector('#stickers-section .stickers');

		plates.forEach(plate => {
			const sticker = document.createElement('div');
			sticker.className = 'sticker';
			sticker.setAttribute('data-plate-index', plate.plateIndex);

			let code = plate.environmentalCode || 'desconocido';
			const img = document.createElement('img');
			img.src = `./images/distintivo_${code}.svg`;
			img.alt = `Distintivo ${code}`;
			img.title = plate.description || '';

			const span = document.createElement('span');
			span.textContent = plate.plate;

			sticker.appendChild(img);
			sticker.appendChild(span);

			sticker.addEventListener('mouseenter', () => {
				const index = sticker.dataset.plateIndex;
				const row = document.querySelector(`.table-row[data-plate-index="${index}"]`);
				if (row) row.classList.add('highlight');
			});

			sticker.addEventListener('mouseleave', () => {
				const index = sticker.dataset.plateIndex;
				const row = document.querySelector(`.table-row[data-plate-index="${index}"]`);
				if (row) row.classList.remove('highlight');
			});

			container.appendChild(sticker);
		});

		document.getElementById('stickers-section').style.visibility = 'visible';
	}
}