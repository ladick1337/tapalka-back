import AdminController from "./AdminController";
import {Response, Request} from "express";
import {Validator} from "js-simple-validator";
import Paginator from "../../../module/Paginator/Paginator";
import Item from "../../../models/Item";
import Role from "../../../models/Role";
import {WhereOptions} from "sequelize/types/model";
import {Op} from "sequelize";
import HttpAppError from "../../HttpAppError";
import ApiPaginationResponse from "../../responses/ApiPaginationResponse";
import ApiResponse from "../../responses/ApiResponse";
import User from "../../../models/User";
import Languages from "../../../consts/Languages";

export default class AdminClientsController extends AdminController
{

	protected async getClientFromRequest(req : Request) : Promise<User>
	{

		let id = Validator.rule.setRequired().isInt().min(1).validate(req.params.id);

		let client = await User.findByPk(id);

		if(!client){
			throw new HttpAppError('Employer not found', 404);
		}

		return client;

	}

	public async list(request : Request, response : Response) : Promise<ApiPaginationResponse<typeof User>>
	{

		let paginator = new Paginator(User);

		paginator.setSortFields(['id', 'username', 'created_at', 'chat_id', 'balance', 'energy', 'energy_charges', 'energy_max', 'energy_level', 'invited_friends']);

		let result = await paginator.build(request, (request : Request) => {

			let {id, is_alive, lang, username, name, chat_id} = Validator.make({
				id: Validator.rule.isInt(),
				chat_id : Validator.rule.isString().length(1, 255),
				lang : Validator.rule.isString().in(Object.values(Languages)),
				username : Validator.rule.isString().length(1, 255),
				name : Validator.rule.isString().length(1, 255),
				is_alive : Validator.rule.isInt().range(0, 1)
			}).validate(request.body);

			let where : WhereOptions<User> = {};

			if(id){ where.id = id; }
			if(chat_id){ where.chat_id = { [Op.like] : `%${chat_id}%` } }
			if(username){ where.username = { [Op.like] : `%${username}%` } }
			if(name){ where.username = { [Op.like] : `%${name}%` } }
			if(lang){ where.lang = lang; }
			if(is_alive !== null){ where.is_alive = +is_alive; }

			return {
				where
			}

		});

		return new ApiPaginationResponse(result);

	}

	public async get(req : Request, res : Response) : Promise<ApiResponse<User>>
	{

		return new ApiResponse(
			await this.getClientFromRequest(req)
		);

	}


	public async edit(req : Request, res : Response) : Promise<ApiResponse<User>>
	{

		let client = await this.getClientFromRequest(req);

		let {balance} = Validator.make({
			balance : Validator.rule.setRequired().isInt().min(0)
		}).validate(req.body);

		client.balance = balance;

		await client.save();

		return new ApiResponse(client);

	}

}