import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Box from "../models/Box";
import Item from "../models/Item";
import BoxItem from "../models/BoxItem";

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
			price : {
				allowNull : false,
				type : DataTypes.DECIMAL(15, 4).UNSIGNED,
				get() {
					return +this.getDataValue('price');
				}
			},
			prises : {
				allowNull : false,
				type : DataTypes.INTEGER.UNSIGNED,
				defaultValue : 1
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

		Box.belongsToMany(Item, {
			through : BoxItem,
			as : 'items',
			onDelete : 'CASCADE'
		});

	}

}