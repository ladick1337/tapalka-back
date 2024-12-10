import Controller from "../Controller";
import {Request, Response} from "express";
import HttpApp from "../../HttpApp";
import Item from "../../../models/Item";
import {HttpAppAdminAuthorizedRequest} from "../../HttpAppRequest";
import {Sequelize} from "sequelize";

export default class AdminController extends Controller
{

	protected admin : Item | null = null;

	constructor(
		app : HttpApp,
		db : Sequelize,
		request : Request,
		response : Response
	) {
		super(app, db, request, response);

		if('admin' in request){
			this.admin = (request as HttpAppAdminAuthorizedRequest).admin;
		}

	}

}