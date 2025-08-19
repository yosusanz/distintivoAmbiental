export class Config {
	constructor() {
		this.APP_NAME = 'Environmental Codes';
		this.API_KEY = 'bd1fe17550bc9d319f02c0cb6c86da619b7158a8dcf06f66e86ce0f53e24acfbc61806770400d0d0045ccc34c7f042f7';
		this.API_URL = `${window.location.protocol}//${window.location.hostname}:${window.location.port ? window.location.port : 80}/api`;
		this.IS_PRODUCTION = false;
		this.IS_OBFUSCATED = true;
	}
}