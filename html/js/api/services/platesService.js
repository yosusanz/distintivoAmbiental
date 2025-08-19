import { Plate } from '/js/api/responses/plate.js';

export class PlatesService {
	constructor(api) {
	  this.api = api;
	  this.urlRoot = this.api.url + '/' + (this.api.config.IS_OBFUSCATED ? 'f778e6c7c1385bc2b2e70fe647ec4f70' : 'plates');
	}

	getEnvironmentalCodesByImage(body, files) {
		return this.api.callForm('POST', this.urlRoot+ '/' + (this.api.config.IS_OBFUSCATED ? '9cd41f8f68aeb6c8d368093c4114213a' : 'getEnvironmentalCodesByImage'), body, files).then(
			data =>{
				if (data.total > 0) {
					data.data = data.data.map(item => new Plate(item));
				}

				return data;
			}
		);
	}
}