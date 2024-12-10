import {DataTypes} from "sequelize";
import Schema from "./Schema";
import Item from "../models/Item";
import Role from "../models/Role";

export default class RoleSchema extends Schema
{

	public init() {

		Role.init({
			id : {
				primaryKey: true,
				autoIncrement: true,
				type: DataTypes.BIGINT.UNSIGNED
			},
			name : {
				allowNull : false,
				type : DataTypes.STRING(255)
			},
			permissions : {
				allowNull : false,
				type : DataTypes.TEXT,
				get(){
					return JSON.parse(this.getDataValue('permissions'))
				},
				set(v){
					this.setDataValue('permissions', JSON.stringify(v));
				}
			},
		}, {
			indexes : [

			],
			sequelize : this.db,
			underscored : true,
			modelName : 'role',
			timestamps : true
		});

	}

	public relations() {
		Role.hasMany(Item);
	}

}