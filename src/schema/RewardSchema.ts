import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Box from "../models/Box";

export default class BoxSchema extends Schema
{

	public init() {

		Box.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			name : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			picture : {
				allowNull : true,
				type : DataTypes.STRING(255)
			},
			description : {
				allowNull : true,
				type : DataTypes.TEXT
			}
		}, {
			indexes : [

			],
			sequelize : this.db,
			underscored : true,
			modelName : 'box',
			timestamps : true
		});

	}

	public relations() {

	}

}