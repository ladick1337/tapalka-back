import Model from "./Model";
import Item from "./Item";
import {BelongsToGetAssociationMixin} from "sequelize";
import Box from "./Box";

export default class BoxItem extends Model
{

	public id! : number;
	public box_id! : number;
	public item_id! : number;
	public quantity! : number;
	public quantity_max! : number;

	public item? : Item;
	public getItem! : BelongsToGetAssociationMixin<Item>;

	public box? : Box;
	public getBox! : BelongsToGetAssociationMixin<Box>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			item_id,
			item,
			quantity,
			quantity_max,
			createdAt
		} = this;

		return {id, item, item_id, quantity, quantity_max, created_at : createdAt};

	}

}