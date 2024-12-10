import {HasManyGetAssociationsMixin} from "sequelize";
import Model from "./Model";
import Item from "./Item";

export default class Role extends Model
{

	public id! : number;
	public name! : string;
	public permissions! : string[];

	public admins? : Item[];
	public getRole! : HasManyGetAssociationsMixin<Item>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			name,
			permissions,
			admins
		} = this;

		return {id, name, permissions, admins };

	}

}