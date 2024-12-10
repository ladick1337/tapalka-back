import {BelongsToGetAssociationMixin, HasManyGetAssociationsMixin} from "sequelize";
import Model from "./Model";
import Box from "./Box";
import User from "./User";
import HistoryReward from "./HistoryReward";
import Item from "./Item";

export default class History extends Model
{
	public id! : number;

	public box_id! : number;
	public box? : Box;
	public getBox! : BelongsToGetAssociationMixin<Box>;

	public user_id! : number;
	public user? : User;
	public getUser! : BelongsToGetAssociationMixin<User>;

	public rewards? : HistoryReward[];
	public getRewards! : HasManyGetAssociationsMixin<HistoryReward>;

	public items? : Item[];
	public getItems! : HasManyGetAssociationsMixin<Item>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			user_id,
			box_id,
			items,
			createdAt
		} = this;

		return {id, user_id, box_id, items, created_at : createdAt};

	}

}