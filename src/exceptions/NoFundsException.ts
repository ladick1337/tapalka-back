export default class NoFundsException extends Error
{

	public constructor() {
		super('No funds');
	}

}