import HttpAppError from "../HttpAppError";

export default class NeedAuthException extends HttpAppError
{

	public constructor() {
		super('Need auth', 401);
	}

}