import {BelongsToGetAssociationMixin, HasManyGetAssociationsMixin, Op, Sequelize} from "sequelize";
import Model from "./Model";
import Asset from "./Asset";
import Token from "./Token";
import {v4 as uuidv4} from 'uuid';
import NoFundsException from "../exceptions/NoFundsException";

export default class User extends Model
{
	public id! : number;
	public wallet! : string;
	public balance! : number;
	public ref_code! : string;
	public ref_count! : number;
	public parent_id! : number | null;
	public profit_passive! : number;
	public profit_game! : number;
	public count_cases! : number;
	public count_prises! : number;

	public parent? : User;
	public getParent! : BelongsToGetAssociationMixin<User>;

	public assets? : Asset[];
	public getAssets! : HasManyGetAssociationsMixin<Asset>;

	public tokens? : Token[];
	public getTokens! : HasManyGetAssociationsMixin<Token>;

	public createdAt! : Date;
	public updatedAt! : Date;

	public async createToken(time : number, extra : any = {}) : Promise<Token>
	{

		return <Token>await Token.create({
			token : uuidv4(),
			user_id : this.id,
			expired_at : Date.now() + time
		}, extra);

	}

	public async restoreBalance(value : number, extra : any = {}) : Promise<void>
	{
		await this.increment({balance : value}, extra);
	}

	public async spendBalance(value : number, extra : any = {}) : Promise<void>
	{

		let [updatedCount] = await (<any>this.constructor).update(
			{
				balance : Sequelize.literal(`balance - ${value}`)
			},
			{
				where: {
					id: (<any>this).id,
					balance : {
						[Op.gte]: value
					}
				},
				...extra
			}
		);

		if(updatedCount < 1){
			throw new NoFundsException;
		}

	}

	public toJSON(): object {

		let {
			id,
			wallet,
			balance,
			ref_code,
			ref_count,
			profit_passive,
			profit_game,
			count_prises,
			count_cases,
			createdAt
		} = this;

		return {id, balance, ref_code, profit_passive, profit_game, count_prises, count_cases, ref_count, wallet, created_at : createdAt};

	}

}