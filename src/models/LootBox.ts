import {HasManyGetAssociationsMixin} from "sequelize";
import Model from "./Model";
import User from "./User";
import TaskTypes from "../consts/TaskTypes";
import Languages from "../consts/Languages";

export default class Task extends Model
{

	public id! : number;
	public title! : string;
	public description! : string;
	public picture! : string;
	public type! : typeof TaskTypes[keyof typeof TaskTypes];
	public lang! : typeof Languages[keyof typeof Languages];
	public url! : string;
	public reward! : number;
	public timeout! : number | null;
	public telegram_channel_id! : string | null;
	public complete_count! : number;

	public users? : User[];
	public getUsers! : HasManyGetAssociationsMixin<User>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			type,
			url,
			timeout,
			reward,
			createdAt,
			title,
			description,
			picture,
			lang,
			complete_count
		} = this;

		return {id, type, url, lang, timeout, reward, created_at : createdAt, title, description, picture, complete_count};

	}

}