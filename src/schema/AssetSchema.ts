import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Item from "../models/Item";
import User from "../models/User";
import Asset from "../models/Asset";

export default class AssetSchema extends Schema
{

	public init() {

		Asset.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			item_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			user_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			quantity : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED
			}
		}, {
			indexes : [
				{
					fields : ['item_id']
				},
				{
					fields : ['user_id']
				},
				{
					fields : ['item_id', 'user_id'],
					unique : true
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'asset',
			timestamps : true
		});

	}

	public relations() {

		Asset.belongsTo(Item, {
			onDelete : 'CASCADE'
		});

		Asset.belongsTo(User, {
			onDelete : 'CASCADE'
		});

	}

}