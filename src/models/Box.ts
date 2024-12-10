import Model from "./Model";
import BoxItem from "./BoxItem";
import {HasManyGetAssociationsMixin} from "sequelize";
import Item from "./Item";

export default class Box extends Model
{

	public id! : number;
	public name! : string;
	public description! : string | null;
	public picture! : string | null;
	public price! : number;
	public prises! : number;

	public createdAt! : Date;
	public updatedAt! : Date;

	public boxItems? : BoxItem[];
	public getBoxItems! : HasManyGetAssociationsMixin<BoxItem>;

	public items? : Item[];
	public getItems! : HasManyGetAssociationsMixin<Item>;

	public toJSON(): object {

		let {
			id,
			name,
			picture,
			description,
			price,
			prises,
			createdAt
		} = this;

		return {id, name, picture, prises, description, price, created_at : createdAt};

	}

}