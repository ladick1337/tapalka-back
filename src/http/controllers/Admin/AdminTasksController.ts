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
import Box from "../../../models/Box";
import TaskTypes from "../../../consts/TaskTypes";
import Languages from "../../../consts/Languages";
import {UploadedFile} from "express-fileupload";

export default class AdminTasksController extends AdminController
{

	protected async getTaskFromRequest(req : Request) : Promise<Box>
	{

		let id = Validator.rule.setRequired().isInt().min(1).validate(req.params.id);

		let task = await Box.findByPk(id);

		if(!task){
			throw new HttpAppError('Роль не найдена', 404);
		}

		return task;

	}

	public async uploadFile(request : Request, response : Response){

		if(request.files && ('file' in request.files) && !(request.files.file instanceof Array)){

			let file = request.files.file;

			let ext = file.name.split('.').pop();
			let name = file.md5 + '.' + ext;
			let path = "/storage/" + name;

			await file.mv('../' + path);

			return new ApiResponse(path);

		}

		throw new HttpAppError('Bad file', 422);


	}

	public async list(request : Request, response : Response) : Promise<ApiPaginationResponse<typeof Box>>
	{

		let paginator = new Paginator(Box);

		paginator.setSortFields(['id', 'name', 'created_at', 'reward', 'complete_count']);

		let result = await paginator.build(request, (request : Request) => {

			let {id, title, type, lang} = Validator.make({
				id : Validator.rule.isString().length(1, 255),
				title : Validator.rule.isString().length(1, 255),
				type : Validator.rule.isString().in(Object.values(TaskTypes)),
				lang : Validator.rule.isString().in(Object.values(Languages))
			}).validate(request.body);

			let where : WhereOptions<Box> = {};

			if(id){ where.id = id; }
			if(title){ where.title = { [Op.like] : `%${title}%` } }
			if(type){ where.type = type; }
			if(lang){ where.lang = lang; }

			return {
				where
			}

		});

		return new ApiPaginationResponse(result);

	}

	public async get(req : Request, res : Response) : Promise<ApiResponse<Box>>
	{

		return new ApiResponse(
			await this.getTaskFromRequest(req)
		);

	}

	public async create(req : Request, res : Response) : Promise<ApiResponse<number>>
	{

		let {type, picture, title, description, timeout, reward, lang, telegram_channel_id, url} = Validator.make({
			type : Validator.rule.setRequired().isString().in(Object.values(TaskTypes)),
			title : Validator.rule.setRequired().isString().length(1, 255),
			description : Validator.rule.setRequired().isString().length(1, 4096),
			timeout : Validator.rule.isInt().min(1).max(20),
			reward : Validator.rule.setRequired().isInt().min(1).max(99999999),
			lang : Validator.rule.setRequired().in(Object.values(Languages)),
			telegram_channel_id : Validator.rule.isString().length(1, 255),
			url : Validator.rule.isString().length(1, 255),
			picture : Validator.rule.isString().length(1, 255)
		}).validate(req.body);

		if(type === TaskTypes.TELEGRAM){

			if(!telegram_channel_id) {
				throw new HttpAppError('Вы не указали CHANNEL ID', 422);
			}

			timeout = 0;

		}

		if(type === TaskTypes.STORY){

			if(!url){
				throw new HttpAppError('Вы не указали картинку для истории');
			}

		}


		let task = await Box.create({
			type,
			title, description, lang,
			timeout,
			reward,
			telegram_channel_id,
			url,
			picture
		});

		return new ApiResponse(task.id);

	}

	public async remove(req : Request, res : Response) : Promise<ApiResponse<null>>
	{

		let task = await this.getTaskFromRequest(req);

		await task.destroy();

		return new ApiResponse(null);

	}

}