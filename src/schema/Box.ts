import {DataTypes} from "sequelize";
import Schema from "./Schema";
import User from "../models/User";
import Task from "../models/Task";
import TasksHistory from "../models/TasksHistory";
import Languages from "../consts/Languages";
import TaskTypes from "../consts/TaskTypes";

export default class TaskSchema extends Schema
{

	public init() {

		Task.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			type : {
				allowNull : false,
				type : DataTypes.ENUM(...Object.values(TaskTypes))
			},
			url : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			title : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			description : {
				allowNull : true,
				type : DataTypes.STRING(255)
			},
			picture : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			timeout : {
				allowNull : true,
				type : DataTypes.INTEGER.UNSIGNED
			},
			complete_count : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED,
				defaultValue : 0
			},
			telegram_channel_id : {
				allowNull : true,
				type : DataTypes.STRING
			},
			reward : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED
			},
			lang : {
				allowNull : false,
				type : DataTypes.ENUM(...Object.values(Languages))
			}
		}, {
			indexes : [
				{
					fields : ['type']
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'task',
			timestamps : true
		});

	}

	public relations() {
		Task.hasMany(TasksHistory);

		Task.belongsToMany(User, {
			through : TasksHistory,
			as : 'users',
			onDelete : 'CASCADE'
		})
	}

}