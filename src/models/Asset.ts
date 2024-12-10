import Model from "./Model";
import {BelongsToGetAssociationMixin} from "sequelize";
import User from "./User";
import Item from "./Item";

export default class Asset extends Model
{

	public id! : number;
	public quantity! : number;

	public item_id! : number;
	public item? : Item;
	public getItem! : BelongsToGetAssociationMixin<Item>;

	public user_id! : number;
	public user? : User;
	public getUser! : BelongsToGetAssociationMixin<User>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			item,
			user_id,
			quantity,
			createdAt
		} = this;

		return {id, item, quantity, user_id, created_at : createdAt };

	}

}