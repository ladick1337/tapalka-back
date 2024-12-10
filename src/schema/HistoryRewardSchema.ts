import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Item from "../models/Item";
import User from "../models/User";
import History from "../models/History";
import Box from "../models/Box";
import HistoryReward from "../models/HistoryReward";

export default class HistoryRewardSchema extends Schema
{

	public init() {

		HistoryReward.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			history_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			item_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
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
					fields : ['history_id']
				},
				{
					fields : ['item_id']
				},
				{
					fields : ['box_id']
				},
				{
					fields : ['user_id']
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'history_reward',
			timestamps : true
		});

	}

	public relations() {

		HistoryReward.belongsTo(Item, {
			onDelete : 'CASCADE'
		});

		HistoryReward.belongsTo(History, {
			onDelete : 'CASCADE'
		});

		HistoryReward.belongsTo(Box, {
			onDelete : 'CASCADE'
		});

		HistoryReward.belongsTo(User, {
			onDelete : 'CASCADE'
		});

	}

}