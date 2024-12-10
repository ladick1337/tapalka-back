import HttpAppError from "../HttpAppError";

export default class NotFoundException extends HttpAppError
{

	public constructor() {
		super('Not found', 404);
	}

}