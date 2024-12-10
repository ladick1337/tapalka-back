import {BelongsToGetAssociationMixin} from "sequelize";
import Model from "./Model";
import Box from "./Box";
import User from "./User";
import Item from "./Item";
import History from "./History";

export default class HistoryReward extends Model
{
	public id! : number;

	public history_id! : number;
	public history? : History;
	public getHistory! : BelongsToGetAssociationMixin<History>;

	public item_id! : number;
	public item? : Item;
	public getItem! : BelongsToGetAssociationMixin<Item>;

	public user_id! : number;
	public user? : User;
	public getUser! : BelongsToGetAssociationMixin<User>;

	public box_id! : number;
	public box? : Box;
	public getBox! : BelongsToGetAssociationMixin<Box>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			history_id,
			item_id,
			box_id,
			user_id,
			createdAt
		} = this;

		return {id, history_id, item_id, box_id, user_id, created_at : createdAt};

	}

}