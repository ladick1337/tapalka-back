import Model from "./Model";
import {BelongsToGetAssociationMixin} from "sequelize";
import Box from "./Box";
import User from "./User";

export default class TasksHistory extends Model
{
	public id! : number;

	public user_id! : string;
	public user? : User;
	public getUser! : BelongsToGetAssociationMixin<User>;

	public task_id! : string;
	public task? : Box;
	public getTask! : BelongsToGetAssociationMixin<Box>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public static async isAvailableForUser(task_id : number, user_id : number) : Promise<boolean>
	{

		let row = await TasksHistory.findOne({
			where : {
				user_id,
				task_id
			}
		});

		return !row;

	}

	public toJSON(): object {

		let {
			id,
			user_id,
			task_id,
			createdAt
		} = this;

		return {id, user_id, task_id, created_at : createdAt};

	}

}