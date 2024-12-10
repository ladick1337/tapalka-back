import {connectDatabase} from "./db";

const args = process.argv.slice(2);
const db = connectDatabase();

(async function(){

	if(args.includes('--fresh')) {
		await db.query('SET FOREIGN_KEY_CHECKS = 0');
		await db.drop();
		await db.query('SET FOREIGN_KEY_CHECKS = 1');
	}

	await db.sync({alter : true, logging : true });

})();
