import {DataTypes} from "sequelize";
import Schema from "./Schema";
import User from "../models/User";
import Asset from "../models/Asset";
import Token from "../models/Token";

export default class UserSchema extends Schema
{

	public init() {

		User.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			wallet : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			balance : {
				allowNull : false,
				type : DataTypes.DECIMAL(15, 4).UNSIGNED,
				defaultValue : 0,
				get() {
					return +this.getDataValue('balance');
				}
			},
			parent_id : {
				allowNull : true,
				type : DataTypes.BIGINT.UNSIGNED
			},
			ref_code : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			ref_count : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED,
				defaultValue : 0
			},
			profit_passive : {
				allowNull : false,
				type : DataTypes.DECIMAL(15, 4).UNSIGNED,
				defaultValue : 0,
				get() {
					return +this.getDataValue('profit_passive');
				}
			},
			profit_game : {
				allowNull : false,
				type : DataTypes.DECIMAL(15, 4).UNSIGNED,
				defaultValue : 0,
				get() {
					return +this.getDataValue('profit_game');
				}
			},
			count_cases : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED,
				defaultValue : 0
			},
			count_prises : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED,
				defaultValue : 0
			},
		}, {
			indexes : [
				{
					fields : ['wallet'],
					unique : true
				},
				{
					fields : ['parent_id']
				},
				{
					fields : ['ref_code'],
					unique : true
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'user',
			timestamps : true
		});

	}

	public relations() {

		User.hasMany(Asset);
		User.hasMany(Token);

		User.belongsTo(User, {
			as : 'parent',
			foreignKey : 'parent_id',
			onDelete : 'SET NULL'
		})

	}

}