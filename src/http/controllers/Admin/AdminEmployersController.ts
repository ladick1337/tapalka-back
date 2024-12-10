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

export default class AdminEmployersController extends AdminController
{

	protected async getEmployerFromRequest(req : Request) : Promise<Item>
	{

		let id = Validator.rule.setRequired().isInt().min(1).validate(req.params.id);

		let employer = await Item.findByPk(id, {
			include : Role
		});

		if(!employer){
			throw new HttpAppError('Employer not found', 404);
		}

		return employer;

	}

	public async list(request : Request, response : Response) : Promise<ApiPaginationResponse<typeof Item>>
	{

		let paginator = new Paginator(Item);

		paginator.setSortFields(['id', 'login', 'role_id', 'created_at']);

		let result = await paginator.build(request, (request : Request) => {

			let {role_id, id, login} = Validator.make({
				role_id : Validator.rule.isInt().range(1, 9999999),
				id : Validator.rule.isString().length(1, 255),
				login : Validator.rule.isString().length(1, 255)
			}).validate(request.body);

			let where : WhereOptions<Item> = {};

			if(role_id){ where.role_id = role_id; }
			if(id){ where.id = id; }
			if(login){ where.login = { [Op.like] : `%${login}%` } }

			return {
				where,
				include : [Role]
			}

		});

		return new ApiPaginationResponse(result);

	}

	public async get(req : Request, res : Response) : Promise<ApiResponse<Item>>
	{

		return new ApiResponse(
			await this.getEmployerFromRequest(req)
		);

	}

	public async create(req : Request, res : Response) : Promise<ApiResponse<number>>
	{

		let {login, password, role_id} = Validator.make({
			login : Validator.rule.setRequired().isString().length(1, 255),
			password : Validator.rule.setRequired().isString().length(1, 255),
			role_id : Validator.rule.setRequired().isInt()
		}).validate(req.body);

		if(await Item.findOne({ where : {login} })){
			throw new HttpAppError('Такой логин уже существует', 422);
		}

		let role = await Role.findByPk(role_id);

		if(!role){
			throw new HttpAppError('Роль не найдена', 422);
		}

		let admin = await Item.create({
			password,
			login,
			role_id
		});

		return new ApiResponse(admin.id);

	}

	public async changePassword(req : Request, res : Response) : Promise<ApiResponse<null>>
	{

		let employer = await this.getEmployerFromRequest(req);

		let {password} = Validator.make({
			password : Validator.rule.setRequired().isString().length(1, 255)
		}).validate(req.body);

		let hash = Item.hashPassword(password);

		if(password === employer.password){
			throw new HttpAppError('Сейчас установлен такой же пароль', 422);
		}

		employer.password = hash;
		await employer.save();

		return new ApiResponse(null);

	}

	public async edit(req : Request, res : Response) : Promise<ApiResponse<Item>>
	{

		let employer = await this.getEmployerFromRequest(req);

		let {login, password, role_id} = Validator.make({
			role_id : Validator.rule.setRequired().isInt()
		}).validate(req.body);

		let role = Role.findByPk(role_id);

		if(!role){
			throw new HttpAppError('Роль не найдена', 422);
		}

		employer.role_id = role_id;

		await employer.save();

		return new ApiResponse(employer);

	}

	public async remove(req : Request, res : Response) : Promise<ApiResponse<null>>
	{

		let employer = await this.getEmployerFromRequest(req);

		await employer.destroy();

		return new ApiResponse(null);

	}

	public async disableTFA(req : Request, res : Response){
		throw new HttpAppError('Cant do that');
	}

	public async authHistory(req : Request, res : Response){
		return new ApiResponse({
			items : [],
			pages : 1,
			count : 0
		})
	}

}