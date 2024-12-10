import {connectDatabase} from "../db";
import Box from "../models/Box";
import Item from "../models/Item";
import BoxItem from "../models/BoxItem";
import User from "../models/User";
import {v4 as uuidv4} from 'uuid';
import Asset from "../models/Asset";

const args = process.argv.slice(2);
const db = connectDatabase();

(async function(){

	await db.transaction(async transaction => {

		//Создадим лутбокс
		let box = await Box.create({
			name : 'Test lootbox',
			price : 200.05,
			prises : 2
		}, {transaction});

		//Создадим юзера
		let user = await User.create({
			wallet : 'test_wallet',
			ref_code : uuidv4().substr(0, 8)
		}, {transaction});

		await user.createToken(500000000, {transaction});

		for(let i = 0; i < 5; i++){

			//Создадим шмотки
			let item = await Item.create({
				name : 'item #' + i,
				price : (i + 1) * 100
			}, {transaction});

			//Добавим их в кейс
			await BoxItem.create({
				item_id : item.id,
				box_id : box.id,
				quantity : 5,
				quantity_max : 5
			}, {transaction})

			//Добавим юзеру в инвентарь
			await Asset.create({
				user_id : user.id,
				item_id : item.id,
				quantity : (i + 1)
			}, {transaction});

		}



	});

})();
