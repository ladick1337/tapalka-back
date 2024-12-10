import {Sequelize} from "sequelize";
import HttpApp from "../HttpApp";
import {Request, Response} from "express";
import Model from "../../models/Model";
import NotFoundException from "../exceptions/NotFoundException";

export default class Controller
{

	protected app : HttpApp;
	protected request : Request;
	protected response : Response;
	protected db : Sequelize;

	public constructor(
		app : HttpApp,
		db : Sequelize,
		request : Request,
		response : Response
	) {
		this.app = app;
		this.db = db;
		this.request = request;
		this.response = response;
	}
	
	protected async getBind<T extends typeof Model>(name : string, model : T) : Promise<InstanceType<T>>{

		let id = +this.request.params[name];

		if(!id){
			throw new NotFoundException;
		}

		let entity = await model.findByPk(id);
		
		if(!entity){
			throw new NotFoundException;
		}
		
		return entity as InstanceType<T>;

	}

}