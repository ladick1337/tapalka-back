import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Item from "../models/Item";
import Asset from "../models/Asset";

export default class ItemSchema extends Schema
{

	public init() {

		Item.init({
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
			},
			price : {
				allowNull : false,
				type : DataTypes.DECIMAL(15, 4).UNSIGNED,
				get() {
					return +this.getDataValue('price');
				}
			}
		}, {
			indexes : [

			],
			sequelize : this.db,
			underscored : true,
			modelName : 'item',
			timestamps : true
		});

	}

	public relations() {

		Item.hasMany(Asset);

	}

}