import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Item from "../models/Item";
import User from "../models/User";
import History from "../models/History";
import Box from "../models/Box";
import HistoryReward from "../models/HistoryReward";

export default class HistorySchema extends Schema
{

	public init() {

		History.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			box_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			user_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			}
		}, {
			indexes : [
				{
					fields : ['box_id']
				},
				{
					fields : ['user_id']
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'history',
			tableName : 'history',
			timestamps : true
		});

	}

	public relations() {

		History.belongsTo(Box, {
			onDelete : 'CASCADE'
		});

		History.belongsTo(User, {
			onDelete : 'CASCADE'
		});

		History.hasMany(HistoryReward);

		History.belongsToMany(Item, {
			through : HistoryReward,
			as : 'rewards',
			onDelete : 'CASCADE'
		});

	}

}