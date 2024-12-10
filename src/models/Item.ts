import Model from "./Model";

export default class Item extends Model
{

	public id! : number;
	public name! : string;
	public price! : number;
	public picture! : string | null;
	public description! : string | null;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			name,
			price,
			picture,
			description,
			createdAt
		} = this;

		return {id, name, price, picture, description, created_at : createdAt };

	}

}