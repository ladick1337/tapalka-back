import HttpAppError from "../HttpAppError";

export default class NoRightsException extends HttpAppError
{

	public constructor() {
		super('Forbidden', 403);
	}

}