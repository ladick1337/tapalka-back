import AdminController from "./AdminController";
import {Response, Request} from "express";
import Role from "../../../models/Role";
import {Validator} from "js-simple-validator";
import Item from "../../../models/Item";
import HttpAppError from "../../HttpAppError";
import Permissions from "../../../consts/Permissions";
import Paginator from "../../../module/Paginator/Paginator";
import {WhereOptions} from "sequelize/types/model";
import {Op} from "sequelize";
import ApiPaginationResponse from "../../responses/ApiPaginationResponse";
import ApiResponse from "../../responses/ApiResponse";

export default class AdminRolesController extends AdminController
{

	protected async getRoleFromRequest(req : Request) : Promise<Role>
	{

		let id = Validator.rule.setRequired().isInt().min(1).validate(req.params.id);

		let role = await Role.findByPk(id);

		if(!role){
			throw new HttpAppError('Роль не найдена', 404);
		}

		return role;

	}

	public async list(request : Request, response : Response) : Promise<ApiPaginationResponse<typeof Role>>
	{

		let paginator = new Paginator(Role);

		paginator.setSortFields(['id', 'name']);

		let result = await paginator.build(request, (request : Request) => {

			let {id, name} = Validator.make({
				id : Validator.rule.isString().length(1, 255),
				name : Validator.rule.isString().length(1, 255)
			}).validate(request.body);

			let where : WhereOptions<Role> = {};

			if(id){ where.id = id; }
			if(name){ where.name = { [Op.like] : `%${name}%` } }

			return {
				where,
				include : [Item]
			}

		});

		return new ApiPaginationResponse(result);

	}

	public async get(req : Request, res : Response) : Promise<ApiResponse<Role>>
	{

		return new ApiResponse(
			await this.getRoleFromRequest(req)
		);

	}

	public async create(req : Request, res : Response) : Promise<ApiResponse<number>>
	{

		let {permissions, name} = Validator.make({
			name : Validator.rule.setRequired().isString().length(1, 255),
			permissions : Validator.rule.setRequired().isArray()
				.assert(p => p.every((i : string) => Object.values(Permissions).includes(i)))
		}).validate(req.body);

		let role = await Role.create({
			name,
			permissions
		});

		return new ApiResponse(role.id);

	}

	public async edit(req : Request, res : Response) : Promise<ApiResponse<Role>>
	{

		let role = await this.getRoleFromRequest(req);

		let {permissions, name} = Validator.make({
			name : Validator.rule.setRequired().isString().length(1, 255),
			permissions : Validator.rule.setRequired().isArray()
				.assert(p => p.every((i : string) => Object.values(Permissions).includes(i)))
		}).validate(req.body);

		role.permissions = permissions;
		role.name = name;

		await role.save();

		return new ApiResponse(role);

	}

	public async remove(req : Request, res : Response) : Promise<ApiResponse<null>>
	{

		let role = await this.getRoleFromRequest(req);

		await role.destroy();

		return new ApiResponse(null);

	}

}