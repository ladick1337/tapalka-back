import {DataTypes} from "sequelize";
import Schema from "./Schema";
import User from "../models/User";
import TasksHistory from "../models/TasksHistory";
import Box from "../models/Box";

export default class TasksHistorySchema extends Schema
{

	public init() {

		TasksHistory.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			user_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			task_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			}
		}, {
			indexes : [
				{
					fields : ['user_id']
				},
				{
					fields : ['task_id']
				},
				{
					fields : ['user_id', 'task_id'],
					unique : true
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'tasks_history',
			timestamps : true
		});

	}

	public relations() {

		TasksHistory.belongsTo(Box, {
			onDelete : 'CASCADE'
		});

		TasksHistory.belongsTo(User, {
			onDelete : 'CASCADE'
		});

	}

}