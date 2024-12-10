import {BelongsToGetAssociationMixin} from "sequelize";
import Model from "./Model";
import User from "./User";

export default class Token extends Model
{
	public id! : number;
	public token! : string;
	public expired_at! : Date;

	public user_id! : number | null;
	public user? : User;
	public getUser! : BelongsToGetAssociationMixin<User>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public toJSON(): object {

		let {
			id,
			token,
			user_id,
			expired_at,
			createdAt
		} = this;

		return {id, token, user_id, expired_at, created_at : createdAt};

	}

}