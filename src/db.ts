import {Sequelize} from "sequelize";
import Config from "./module/Config";
import UserSchema from "./schema/UserSchema";
import BoxSchema from "./schema/BoxSchema";
import ItemSchema from "./schema/ItemSchema";
import AssetSchema from "./schema/AssetSchema";
import TokenSchema from "./schema/TokenSchema";
import BoxItemSchema from "./schema/BoxItemSchema";

function init_models(db : Sequelize){

	let schemas = [
		new UserSchema(db),
		new TokenSchema(db),
		new ItemSchema(db),
		new BoxSchema(db),
		new BoxItemSchema(db),
		new AssetSchema(db),
	];

	schemas.forEach(schema => schema.init());
	schemas.forEach(schema => schema.relations());

}

export function connectDatabase() : Sequelize
{

	let db = new Sequelize(
		Config.DB_NAME,
		Config.DB_LOGIN,
		Config.DB_PASSWORD,
		{
			host : Config.DB_HOST,
			dialect : Config.DB_DIALECT,
			logging: Config.DB_LOGGING && ((msg) => console.log(msg))
		}
	);

	init_models(db);

	return db;

}