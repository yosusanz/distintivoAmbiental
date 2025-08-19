export class Api {
	url;
	config;

	constructor(config) {
		this.url = config.API_URL;
		this.config = config;
	}

	callForm(callMethod, url, body = null, files = []) {
		const formData = new FormData();

		if (body) {
			formData.append("data", JSON.stringify(body));
		}
		if (files && files.length > 0) {
			files.forEach((f, i) => formData.append(`files[${i}]`, f));
		}

		let headers = {
			'API-KEY': this.config.API_KEY
		};

		return fetch(url, {
			method: callMethod,
			body: formData,
			headers: headers
		}).then(function (response) {
			if (response.ok) {
				return response.json();
			} else {
				return Promise.reject(response);
			}
		}).then(function (data) {
			return data;
		}).catch(function (err) {
			return { error: true, total: 0, data: {} };
		});
	}
}