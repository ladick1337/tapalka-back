import AdminController from "./AdminController";
import HttpApp from "../../HttpApp";
import {Validator} from "js-simple-validator";
import Item from "../../../models/Item";
import HttpAppError from "../../HttpAppError";
import {Response, Request} from "express";
import ApiResponse from "../../responses/ApiResponse";

export default class AdminAuthController extends AdminController
{

	public async login(req : Request, res : Response) : Promise<ApiResponse<string>>
	{

		let {login, password} = Validator.make({
			login : Validator.rule.setRequired().isString().length(1, 255),
			password : Validator.rule.setRequired().isString().length(1, 255)
		}).validate(req.body);

		let admin = await Item.findOne({
			where : { login }
		});

		if(!admin || Item.hashPassword(password) !== admin.password){
			throw new HttpAppError('Неверный логин или пароль', 403);
		}

		await admin.refreshToken()

		return new ApiResponse(admin.token!);

	}

}