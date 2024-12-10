import AdminController from "./AdminController";
import {Response, Request} from "express";
import HttpAppError from "../../HttpAppError";
import {HttpAppAdminAuthorizedRequest} from "../../HttpAppRequest";
import {Validator} from "js-simple-validator";
import Item from "../../../models/Item";
import ApiResponse from "../../responses/ApiResponse";

export default class AdminProfileController extends AdminController
{

	public generateTFA(){
		throw new HttpAppError('Cant do that');
	}

	public confirmTFA(){
		throw new HttpAppError('Cant do that');
	}

	public removeTFA(){
		throw new HttpAppError('Cant do that');
	}

	public async me(req : Request, res : Response) : Promise<ApiResponse<Item>>
	{
		return new ApiResponse(this.admin!);
	}

	public authHistory(req : Request, res : Response){

		return new ApiResponse({
			items : [],
			count : 0,
			pages : 1
		});

	}

	public async changePassword(req : Request, res : Response) : Promise<ApiResponse<null>>
	{

		let {password} = Validator.make({
			password : Validator.rule.setRequired().isString().length(1, 255)
		}).validate(req.body);

		let hash = Item.hashPassword(password);

		if(hash === this.admin!.password){
			throw new HttpAppError('Сейчас установлен такой же пароль', 422);
		}

		this.admin!.password = hash;
		await this.admin!.save();

		return new ApiResponse(null);

	}

	public async logout(req : Request, res : Response) : Promise<ApiResponse<null>>
	{

		await this.admin!.clearToken();

		return new ApiResponse(null);

	}

}