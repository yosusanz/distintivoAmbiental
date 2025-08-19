export class Plate {
	plateIndex;
    plate;
    plateImage;
    confidence;
    environmentalCode;
    description;
  
    constructor(data = null) {
        this.plateIndex = data?.plateIndex;
        this.plate = data?.plate;
        this.plateImage = data?.plateImage;
        this.confidence = data?.confidence;
        this.environmentalCode = data?.environmentalCode;
        this.description = data?.description;
    }
  }
