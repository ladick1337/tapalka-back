import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Box from "../models/Box";
import BoxItem from "../models/BoxItem";
import Item from "../models/Item";

export default class BoxItemSchema extends Schema
{

	public init() {

		BoxItem.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			item_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			box_id : {
				allowNull : true,
				type : DataTypes.BIGINT.UNSIGNED
			},
			quantity : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED
			},
			quantity_max : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED
			}
		}, {
			indexes : [
				{ fields : ['box_id'] },
				{ fields: ['item_id'] }
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'box_item',
			timestamps : true
		});

	}

	public relations() {

		BoxItem.belongsTo(Box, {
			onDelete : 'CASCADE'
		});

		BoxItem.belongsTo(Item);

	}

}