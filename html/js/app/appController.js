import { Config } from '/js/Config.js';
import { Api } from '/js/api/Api.js';

import { AppView } from '/js/app/appView.js';

import { PlatesService } from '/js/api/services/platesService.js';

import Toastify from '/js/others/toastify/toastify-es.js'

export class AppController {
	api;
	config;
	appView;

	constructor() {
		this.config = new Config();

		this.api = new Api(this.config);

		this.appView = new AppView(this);
		this.appView.init();

		this.platesService = new PlatesService(this.api);
	}

	async init() {
		this.config.IS_PRODUCTION && this.preventDevMode();
	}

	preventDevMode(){
		document.addEventListener('contextmenu', (e) => e.preventDefault());

		document.onkeydown = (e) => {
			if (
				e.key === 'F12' ||
				this.ctrlShiftKey(e, 'i') ||
				this.ctrlShiftKey(e, 'j') ||
				this.ctrlShiftKey(e, 'c') ||
				(e.ctrlKey && e.key.toLowerCase() === 'u')
			)
				return false;
		};
	}

	ctrlShiftKey(e, key) {
		return e.ctrlKey && e.shiftKey && e.key.toLowerCase() === key;
	}

	showLoadingScreen(message) {
		const loadingScreen = document.getElementById('loading-container');
		const loadingMessage = document.getElementById('loading-message');
		loadingMessage.innerHTML = message;

		loadingScreen.classList.add('show');
	}
	hideLoadingScreen() {
		const loadingScreen = document.getElementById('loading-container');
		const loadingMessage = document.getElementById('loading-message');
		loadingMessage.innerHTML = '';

		loadingScreen.classList.remove('show');
	}

	showToast(data) {
		const toast = Toastify({
			text: data.text,
			className: data.type,
			duration: 3000,
			newWindow: true,
			close: true,
			gravity: "bottom",
			position: "right",
			stopOnFocus: true,
		});

		toast.showToast();
	}

	async callApi(service, command, body, files) {
		const data = await this[service][command](body, files);
		if (!data) {
			const criticalText = 'Error crítico de comunicacion';
			this.showToast({ type: 'critical', text: criticalText })
		} else {
			if (data.error) {
				const errorText = 'Error en la respuesta';
				this.showToast({ type: 'error', text: errorText })
			} else {
				return data
			}
		}
	}
}

const app = new AppController();
window.addEventListener('load', () => app.init());