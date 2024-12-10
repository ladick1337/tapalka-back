import {DataTypes} from "sequelize";
import Schema from "./Schema";
import User from "../models/User";
import Token from "../models/Token";

export default class TokenSchema extends Schema
{

	public init() {

		Token.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			user_id : {
				allowNull : false,
				type : DataTypes.BIGINT.UNSIGNED
			},
			token : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			expired_at : {
				allowNull : true,
				type : DataTypes.DATE
			},
		}, {
			indexes : [
				{
					fields : ['token'],
					unique : true
				},
				{
					fields : ['user_id']
				}
			],
			sequelize : this.db,
			underscored : true,
			modelName : 'token',
			timestamps : true
		});

	}

	public relations() {

		Token.belongsTo(User, {
			onDelete : 'CASCADE'
		});

	}

}